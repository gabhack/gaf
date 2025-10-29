set -e
git switch -c debug/dias-laborados-trace 2>/dev/null || git checkout -b debug/dias-laborados-trace

mkdir -p app/Support
cat > app/Support/DiasLaboradosLogger.php <<'PHP'
<?php
namespace App\Support;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DiasLaboradosLogger
{
    public static function fromItems(string $tag, $items): void
    {
        $col = $items instanceof Collection ? $items : collect($items);
        $summary = [
            'count'   => $col->count(),
            'nulls'   => $col->where('dias_laborados', null)->count(),
            'numeric' => $col->filter(function ($i) {
                $v = is_array($i) ? ($i['dias_laborados'] ?? null) : ($i->dias_laborados ?? null);
                return is_numeric($v);
            })->count(),
            'strings' => $col->filter(function ($i) {
                $v = is_array($i) ? ($i['dias_laborados'] ?? null) : ($i->dias_laborados ?? null);
                return is_string($v);
            })->count(),
        ];
        $sample = $col->take(20)->map(function ($i) {
            if (is_array($i)) {
                return [
                    'code' => $i['code'] ?? null,
                    'doc'  => $i['doc'] ?? null,
                    'periodo' => $i['periodo'] ?? null,
                    'dias_laborados' => $i['dias_laborados'] ?? null,
                ];
            }
            return [
                'code' => $i->code ?? null,
                'doc'  => $i->doc ?? null,
                'periodo' => $i->periodo ?? null,
                'dias_laborados' => $i->dias_laborados ?? null,
            ];
        });
        Log::info('DIAS_TRACE_SUMMARY', ['tag' => $tag, 'summary' => $summary]);
        Log::info('DIAS_TRACE_SAMPLE', ['tag' => $tag, 'sample' => $sample]);
    }
}
PHP

mkdir -p app/Http/Middleware
cat > app/Http/Middleware/TraceDiasLaborados.php <<'PHP'
<?php
namespace App\Http\Middleware;
use App\Support\DiasLaboradosLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TraceDiasLaborados
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            Log::info('DIAS_MW_REQUEST', [
                'path' => $request->path(),
                'query' => $request->query(),
                'headers' => ['accept' => $request->header('accept')]
            ]);
        } catch (\Throwable $e) {
        }

        $response = $next($request);

        try {
            $ct = $response->headers->get('content-type');
            if ($ct && strpos($ct, 'application/json') !== false) {
                $json = json_decode($response->getContent(), true);
                if (is_array($json)) {
                    $items = $json['items'] ?? ($json['data']['items'] ?? ($json['data'] ?? null));
                    if (is_array($items)) {
                        DiasLaboradosLogger::fromItems('middleware_response', $items);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('DIAS_MW_ERROR', ['error' => $e->getMessage()]);
        }

        return $response;
    }
}
PHP

mkdir -p app/Providers
cat > app/Providers/AppServiceProvider.php <<'PHP'
<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\TraceDiasLaborados;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(Router $router): void
    {
        $router->pushMiddlewareToGroup('api', TraceDiasLaborados::class);
    }
}
PHP

mkdir -p resources/assets/js/debug
cat > resources/assets/js/debug/traceDias.js <<'JS'
export default function traceDias(stage, ctx = {}) {
  try {
    const clean = (val) => {
      if (Array.isArray(val)) return val.slice(0, 20);
      return val;
    };
    let items = null;
    if (ctx && ctx.couponsIngresos && Array.isArray(ctx.couponsIngresos.items)) {
      items = ctx.couponsIngresos.items;
    } else if (ctx && Array.isArray(ctx.items)) {
      items = ctx.items;
    }
    const sample = items
      ? items.slice(0, 20).map(i => ({
          code: i.code ?? null,
          doc: i.doc ?? null,
          periodo: i.periodo ?? null,
          dias_laborados: i.dias_laborados ?? null
        }))
      : null;
    const summary = items
      ? {
          count: items.length,
          nulls: items.filter(i => (i?.dias_laborados ?? null) === null).length,
          numeric: items.filter(i => !isNaN(Number(i?.dias_laborados))).length
        }
      : null;
    console.groupCollapsed(`[DIAS][${stage}]`);
    if (ctx.selectedPeriod !== undefined) console.log('selectedPeriod', clean(ctx.selectedPeriod));
    if (ctx.pagaduria !== undefined) console.log('pagaduria', clean(ctx.pagaduria));
    if (ctx.pagaduriaPeriodos !== undefined) console.log('pagaduriaPeriodos', clean(ctx.pagaduriaPeriodos));
    if (ctx.route) console.log('route', clean(ctx.route));
    if (summary) console.log('items.summary', summary);
    if (sample) console.table(sample);
    console.log('raw.ctx', ctx);
    console.groupEnd();
  } catch (e) {
    console.error('[DIAS][traceDias][error]', e);
  }
}
JS

cat > resources/assets/js/debug/registerDebugMixins.js <<'JS'
import Vue from 'vue';
import traceDias from './traceDias';

if (!window.__DIAS_DEBUG_ACTIVE) {
  window.__DIAS_DEBUG_ACTIVE = true;

  const tryWatch = (vm, key, deep = false, label = key) => {
    if (key in vm || (vm.$data && Object.prototype.hasOwnProperty.call(vm.$data, key))) {
      vm.$watch(key, (n, o) => {
        traceDias(`${vm.$options.name || 'anon'}:${label} changed`, {
          route: vm.$route ? { name: vm.$route.name, path: vm.$route.path, params: vm.$route.params, query: vm.$route.query } : null,
          [label]: n,
          couponsIngresos: vm.couponsIngresos,
          arrayCoupons: vm.arrayCoupons,
          selectedPeriod: vm.selectedPeriod,
          pagaduria: vm.pagaduria,
          pagaduriaPeriodos: vm.pagaduriaPeriodos
        });
      }, { deep });
    }
  };

  Vue.mixin({
    mounted() {
      traceDias(`${this.$options.name || 'anon'}:mounted`, {
        route: this.$route ? { name: this.$route.name, path: this.$route.path, params: this.$route.params, query: this.$route.query } : null,
        couponsIngresos: this.couponsIngresos,
        arrayCoupons: this.arrayCoupons,
        selectedPeriod: this.selectedPeriod,
        pagaduria: this.pagaduria,
        pagaduriaPeriodos: this.pagaduriaPeriodos
      });
      tryWatch(this, 'selectedPeriod', false, 'selectedPeriod');
      tryWatch(this, 'pagaduria', false, 'pagaduria');
      tryWatch(this, 'pagaduriaPeriodos', true, 'pagaduriaPeriodos');
      tryWatch(this, 'couponsIngresos', true, 'couponsIngresos');
      tryWatch(this, 'arrayCoupons', true, 'arrayCoupons');
    }
  });
}
export {};
JS

APPJS_A=resources/assets/js/app.js
APPJS_B=resources/js/app.js
if [ -f "$APPJS_A" ]; then
  tmpfile=$(mktemp)
  printf '%s\n' 'import "./debug/registerDebugMixins"' | cat - "$APPJS_A" > "$tmpfile"
  mv "$tmpfile" "$APPJS_A"
elif [ -f "$APPJS_B" ]; then
  tmpfile=$(mktemp)
  printf '%s\n' 'import "./debug/registerDebugMixins"' | cat - "$APPJS_B" > "$tmpfile"
  mv "$tmpfile" "$APPJS_B"
fi

php artisan optimize:clear || true
composer dump-autoload -o || true

git add app/Support/DiasLaboradosLogger.php app/Http/Middleware/TraceDiasLaborados.php app/Providers/AppServiceProvider.php resources/assets/js/debug/traceDias.js resources/assets/js/debug/registerDebugMixins.js $APPJS_A $APPJS_B 2>/dev/null || true
git commit -m "chore(debug): trazas dias_laborados en backend y frontend"
