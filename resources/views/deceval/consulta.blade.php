@extends('layouts.app2')

@section('styles')
    <style>
        /* Estilos personalizados para los selects */
        .select-custom {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: #fff;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1em;
        }

        .select-custom:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: 0;
        }

        .select-custom option {
            background: #fff;
            color: #212529;
            padding: 0.5rem;
        }

        .select-custom option:checked {
            background-color: #e9ecef !important;
            color: #212529 !important;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            border-radius: 0.25rem;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
        }

        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 0.75rem 1.25rem;
            color: inherit;
        }

        .fa-exclamation-triangle {
            margin-right: 8px;
        }

        /* Estilos para el cuestionario */
        .exam-container {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 15px;
        }

        .question-card {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .question-card:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .question-text {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .answers-container {
            margin-left: 20px;
        }

        .form-check-input:checked+.form-check-label {
            font-weight: bold;
            color: #218838;
        }

        /* Estilos para el proceso OTP */
        .otp-method-select {
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .otp-method-select:hover {
            background-color: #f8f9fa;
            border-left-color: #007bff;
        }

        .otp-method-select i {
            width: 20px;
            text-align: center;
        }

        #otp-verification {
            max-width: 500px;
            margin: 0 auto;
        }

        #otp_code {
            letter-spacing: 5px;
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    @if (isset($error))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-exclamation-triangle"></i> {{ $error }}</strong>
                    @if (isset($error_type) && $error_type === 'identity_verification')
                        <p class="mt-2">Por favor verifica que los datos ingresados sean correctos o contacta al soporte
                            técnico.</p>
                    @endif
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="container-fluid">
            <div class="vld-overlay is-active is-full-page" aria-label="Loading" tabindex="0" style="display: none;">
                <div class="vld-background"></div>
                <div class="vld-icon">
                    <svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" width="64" height="64"
                        stroke="#0CEDB0">
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)" stroke-width="2">
                                <circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle>
                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                    <animateTransform type="rotate" attributeName="transform" from="0 18 18" to="360 18 18"
                                        dur="0.8s" repeatCount="indefinite"></animateTransform>
                                </path>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>
            <div>
                <form class="form-group col-md-12" action="{{ url('deceval/firmar') }}" method="post">
                    <input id="step" name="step" type="hidden" value="{{ $step ?? 'initial' }}" />
                    <input id="validation" name="validation" type="hidden" value="{{ $validation ?? '' }}" />
                    <input id="phoneListStr" name="phoneListStr" type="hidden" value="{{ $phoneListStr ?? '' }}" />
                    <input id="mailListStr" name="mailListStr" type="hidden" value="{{ $mailListStr ?? '' }}" />
                    <input id="application" name="application" type="hidden" value="{{ $application ?? '' }}" />
                    <input id="nombrelibranza" name="nombrelibranza" type="hidden" value="{{ $documentName ?? '' }}" />

                    {{ csrf_field() }}

                    <div class="row" id="consulta-container">
                        <div class="col-12">
                            <div class="panel mb-3">
                                <div class="panel-heading">
                                    <b>RESULTADO DE LA CONSULTA</b>
                                </div>

                                <div class="panel-body">

                                    @if ($step === 'phone-selection')
                                        <!-- Paso 1: Selección de método de envío OTP -->
                                        <div id="otp-method-selection">
                                            <h4>Seleccione el método para recibir su código de verificación:</h4>

                                            <div class="row mt-4">
                                                <!-- Teléfonos móviles -->
                                                <div class="form-group col-md-6">
                                                    <label class="font-weight-bold">Teléfonos móviles:</label>
                                                    <div class="list-group">
                                                        @foreach ($phoneList as $phone)
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action otp-method-select"
                                                                data-type="mobile" data-phone="{{ $phone }}"
                                                                data-method="SMS">
                                                                <i class="fas fa-mobile-alt mr-2"></i>
                                                                {{ $phone }} (SMS)
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Teléfonos fijos -->
                                                @if (!empty($landLineList))
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-bold">Teléfonos fijos:</label>
                                                        <div class="list-group">
                                                            @foreach ($landLineList as $phone)
                                                                <a href="#"
                                                                    class="list-group-item list-group-item-action otp-method-select"
                                                                    data-type="landline" data-phone="{{ $phone }}"
                                                                    data-method="Voice">
                                                                    <i class="fas fa-phone mr-2"></i>
                                                                    {{ $phone }} (Llamada)
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Correos electrónicos -->
                                                @if (!empty($emailList))
                                                    <div class="form-group col-md-6">
                                                        <label class="font-weight-bold">Correos electrónicos:</label>
                                                        <div class="list-group">
                                                            @foreach ($emailList as $email)
                                                                <a href="#"
                                                                    class="list-group-item list-group-item-action otp-method-select"
                                                                    data-type="email" data-email="{{ $email }}"
                                                                    data-method="Email">
                                                                    <i class="fas fa-envelope mr-2"></i>
                                                                    {{ $email }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Campos ocultos para el formulario -->
                                            <input type="hidden" name="selected_phone" id="selected_phone">
                                            <input type="hidden" name="selected_email" id="selected_email">
                                            <input type="hidden" name="phone_type" id="phone_type">
                                            <input type="hidden" name="validation_method" id="validation_method">
                                        </div>
                                    @elseif($step === 'otp-verification')
                                        <!-- Paso 2: Verificación del código OTP -->
                                        <div id="otp-verification">
                                            <h4>Verificación de código OTP</h4>
                                            <p class="text-muted">Hemos enviado un código de 6 dígitos a
                                                @if ($validationMethod === 'Email')
                                                    <strong>{{ $selectedEmail }}</strong> vía correo electrónico.
                                                @else
                                                    <strong>{{ $selectedPhone }}</strong> vía
                                                    {{ $validationMethod === 'SMS' ? 'SMS' : 'llamada de voz' }}.
                                                @endif
                                            </p>

                                            <div class="row mt-4">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="otp_code">Código OTP</label>
                                                        <input type="text" class="form-control" id="otp_code"
                                                            name="code" maxlength="6"
                                                            placeholder="Ingrese el código de 6 dígitos">
                                                    </div>

                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <div id="otp-timer" class="text-primary font-weight-bold">
                                                            Tiempo restante: 02:00
                                                        </div>

                                                        <button type="button" id="resend-otp-btn"
                                                            class="btn btn-link text-primary" style="display: none;"
                                                            disabled>
                                                            Reenviar código
                                                        </button>
                                                    </div>

                                                    <div class="mt-3">
                                                        <button type="submit" class="btn btn-primary">
                                                            Verificar código
                                                        </button>

                                                        <button type="button" id="change-method-btn"
                                                            class="btn btn-link text-danger ml-2">
                                                            Cambiar método de envío
                                                        </button>
                                                    </div>

                                                    <!-- Opción de bypass después de cierto tiempo -->
                                                    <div id="bypass-option" class="mt-3" style="display: none;">
                                                        <hr>
                                                        <p class="text-muted">¿No recibiste el código?</p>
                                                        <button type="button" id="bypass-otp-btn"
                                                            class="btn btn-outline-secondary">
                                                            Validar con preguntas de seguridad
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($step === 'otp-success')
                                        <!-- Paso 3: OTP verificado exitosamente -->
                                        <div class="alert alert-success">
                                            <h4><i class="fas fa-check-circle"></i> Verificación exitosa</h4>
                                            <p>Tu identidad ha sido verificada correctamente.</p>
                                        </div>

                                        <!-- Continuar con el proceso de firma -->
                                        <div class="text-center mt-4">
                                            <a href="{{ asset('storage/pdfs/firmados/' . $documentName . '.pdf') }}"
                                                class="btn btn-primary" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Ver documento firmado
                                            </a>
                                        </div>
                                    @endif

                                    @if (($step ?? 'initial') === 'confirmCode' || ($step ?? 'initial') === 'initial')
                                        @if (($step ?? 'initial') === 'initial')
                                            @if (isset($validation) && $validation === 'PhoneSelection')
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <b class="panel-label">TELÉFONO DE CONFIRMACIÓN:</b>
                                                        <select class="form-control select-custom" id="phone"
                                                            name="phone" required="required">
                                                            @foreach ($phoneList ?? [] as $phone)
                                                                <option value="{{ $phone }}"
                                                                    {{ old('phone') == $phone ? 'selected' : '' }}>
                                                                    {{ $phone }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <b class="panel-label">CORREO DE CONFIRMACIÓN:</b>
                                                        <select class="form-control select-custom" id="email"
                                                            name="email" required="required">
                                                            @foreach ($mailList ?? [] as $mail)
                                                                <option value="{{ $mail }}"
                                                                    {{ old('email') == $mail ? 'selected' : '' }}>
                                                                    {{ $mail }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif (isset($validation) && $validation === 'ShowExam')
                                                @isset($exam)
                                                    @csrf
                                                    <textarea name="examData" style="display:none">{{ $examData }}</textarea>
                                                    <input type="hidden" name="step" value="answerExam" />
                                                    <input type="hidden" name="validation" value="ShowExam" />
                                                    <input type="hidden" name="application" value="{{ $application }}" />
                                                    <input type="hidden" name="nombrelibranza"
                                                        value="{{ $documentName }}" />

                                                    <!-- Preguntas y respuestas -->
                                                    <div class="exam-container">
                                                        @foreach ($exam->Questions->Question as $index => $question)
                                                            <div class="question-card">
                                                                <h5 class="question-text">{{ $question['Text'] }}</h5>
                                                                <div class="answers-container">
                                                                    @foreach ($question->Answer as $i => $answer)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="answers[{{ $question['QuestionId'] }}]"
                                                                                id="q{{ $index }}_a{{ $i }}"
                                                                                value="{{ trim((string) $answer) }}" required>
                                                                            <label class="form-check-label"
                                                                                for="q{{ $index }}_a{{ $i }}">
                                                                                {{ trim((string) $answer) }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <button type="submit" class="btn btn-primary mt-3">
                                                        Validar Respuestas
                                                    </button>
                                                @else
                                                    <div class="alert alert-danger">
                                                        Error: No se pudo cargar el cuestionario
                                                    </div>
                                                @endisset
                                            @endif
                                        @endif

                                        @if (($confirmCode ?? '') === 'confirmCode')
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <b class="panel-label">CÓDIGO DE CONFIRMACIÓN:</b>
                                                    <input id="code" name="code" type="text"
                                                        class="form-control" value="" required="required" />
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="row mt-3">
                                        <div class="form-group col-md-6">
                                            @if (($step ?? '') === 'confirmCode')
                                                <button class="btn btn-secondary" type="submit">
                                                    <i class="fa fa-pencil"></i> Confirmar Código
                                                </button>
                                            @elseif (($step ?? '') === 'initial' && ($validation ?? '') !== 'ShowExam')
                                                <button class="btn btn-secondary" type="submit">
                                                    <i class="fa fa-pencil"></i> Enviar Código
                                                </button>
                                            @elseif(($step ?? '') === 'failCreateFlow')
                                                <button class="btn btn-secondary" type="submit">
                                                    <i class="fa fa-pencil"></i> Regenerar Flujo
                                                </button>
                                            @elseif(($step ?? '') === 'firmado')
                                                <div class="form-group">
                                                    <a class="btn btn-primary"
                                                        href="{{ asset('storage/pdfs/firmados/' . ($documentName ?? '') . '.pdf') }}"
                                                        target="_blank">
                                                        <i class="fa fa-envelope-open"></i> Abrir Libranza
                                                    </a>
                                                </div>
                                                @if (isset($documentName) && file_exists(storage_path('app/public/pdfs/firmados/' . $documentName . '.pdf')))
                                                    <iframe
                                                        src="{{ asset('storage/pdfs/firmados/' . $documentName . '.pdf') }}"
                                                        scrolling="auto" height="1100" width="100%"
                                                        frameborder="0"></iframe>
                                                @else
                                                    <div class="alert alert-warning mt-3">
                                                        El documento firmado no está disponible.
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @if (($step ?? '') === 'initial' && ($validation ?? '') !== 'ShowExam')
                                        @if (isset($documentName) && file_exists(storage_path('app/public/pdfs/sinfirmar/' . $documentName . '.pdf')))
                                            <iframe src="{{ asset('storage/pdfs/sinfirmar/' . $documentName . '.pdf') }}"
                                                scrolling="auto" height="1100" width="100%" frameborder="0"></iframe>
                                        @else
                                            <div class="alert alert-warning mt-3">
                                                El documento sin firmar no está disponible.
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@section('title')
    Consulta Deceval
@endsection

@section('header-content')
    Consulta Deceval
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li><a href="{{ url('deceval') }}">Deceval</a></li>
    <li class="active">Consulta</li>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Selección de método OTP
            $('.otp-method-select').click(function(e) {
                e.preventDefault();

                const method = $(this).data('method');
                const type = $(this).data('type');

                $('#validation_method').val(method);
                $('#phone_type').val(type === 'email' ? '' : (type === 'mobile' ? 'Mobile' : 'Landline'));

                if (type === 'email') {
                    $('#selected_email').val($(this).data('email'));
                    $('#selected_phone').val('');
                } else {
                    $('#selected_phone').val($(this).data('phone'));
                    $('#selected_email').val('');
                }

                // Actualizar el formulario y enviar
                $('input[name="step"]').val('otp-verification');
                $('form').submit();
            });

            // Temporizador para OTP
            @if ($step === 'otp-verification')
                let timeLeft = 120; // 2 minutos en segundos
                const otpTimer = $('#otp-timer');
                const resendBtn = $('#resend-otp-btn');
                const bypassOption = $('#bypass-option');

                const timer = setInterval(function() {
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;

                    otpTimer.text(
                        `Tiempo restante: ${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                        );

                    // Mostrar botón de reenvío después de 30 segundos
                    if (timeLeft <= 90 && resendBtn.is(':hidden')) {
                        resendBtn.show().prop('disabled', false);
                    }

                    // Mostrar opción de bypass cuando el tiempo se agote
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        bypassOption.show();
                    }

                    timeLeft--;
                }, 1000);

                // Reenviar OTP
                resendBtn.click(function() {
                    if ($(this).prop('disabled')) return;

                    $(this).prop('disabled', true).html(
                        '<i class="fas fa-spinner fa-spin"></i> Enviando...');

                    // Aquí puedes hacer una llamada AJAX para reenviar el OTP
                    // o simplemente enviar el formulario con una acción diferente
                    $('input[name="step"]').val('resend-otp');
                    $('form').submit();
                });

                // Cambiar método de envío
                $('#change-method-btn').click(function() {
                    $('input[name="step"]').val('phone-selection');
                    $('form').submit();
                });

                // Bypass OTP (preguntas de seguridad)
                $('#bypass-otp-btn').click(function() {
                    if (confirm(
                            '¿Estás seguro de que deseas validar tu identidad con preguntas de seguridad?'
                            )) {
                        $('input[name="step"]').val('bypass-otp');
                        $('form').submit();
                    }
                });
            @endif

            // Validación del código OTP antes de enviar
            $('form').on('submit', function() {
                if ($('input[name="step"]').val() === 'otp-verification') {
                    const otpCode = $('#otp_code').val().trim();
                    if (otpCode.length !== 6 || !/^\d+$/.test(otpCode)) {
                        alert('Por favor ingrese un código OTP válido de 6 dígitos');
                        return false;
                    }
                }
                return true;
            });
        });
    </script>
@endsection
