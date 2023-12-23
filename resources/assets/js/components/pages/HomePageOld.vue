<template>
  <div>
    <section class="container-fluid mb-5">
      <div class="row">
        <div class="col-sm-12 img-responsive">
          <img src="/img/home-top-illustration.svg" class="img-fluid w-100" />
        </div>
        <div
          class="
            offset-1
            col-lg-4 col-sm-12
            d-flex
            align-items-center
            marg-resp
          "
        >
          <div class="pr-md-5 res-center">
            <p class="text-big font-weight-bold">
              No tomes <br />
              más decisiones <br />
              <span class="text-spring-green fancy-text mb-2">a ciegas</span>
            </p>
            <p class="p-text2">
              AMI tu consultor amigo esta
              <strong>disponible las 24/7</strong> consulta pregunta interactúa
              con el y toma
              <strong>
                las mejores decisiones financieras para tu futuro.
              </strong>
            </p>
            <b-button v-b-modal.modal-tall variant="black" class="btn-more">
              Conoce más
            </b-button>

            <b-modal id="modal-tall">
              <div class="row content-popsec1">
                <div class="col-lg-12 col-sm-12">
                  <p class="fancy-text">Nuestras</p>
                  <span class="text-big-popup d-block text-uppercase">
                    soluciones
                  </span>
                </div>
              </div>
              <div class="row content-pop2">
                <div class="col-lg-4 col-sm-12">
                  <img
                    src="/img/coheteicon.svg"
                    alt="img-asesoria"
                    width="70%"
                  />
                </div>
                <div class="col-lg-8 col-sm-12">
                  <p class="text-popup pad-sec1">
                    <span class="font-weight-bold text-spring-green"
                      >Diseñadas
                    </span>
                    para las compañías originadoras<br />
                    de créditos: La usabilidad hace parte de<br />
                    nuestros pilares, sabemos que cada cliente<br />
                    tiene diferentes procesos, productos, por eso<br />
                    nuestras herramientas son fáciles de usar.
                  </p>
                </div>
              </div>
              <div class="row content-pop2">
                <div class="col-lg-8 col-sm-12">
                  <p class="text-popup pad-sec1">
                    <span class="font-weight-bold text-spring-green"
                      >Innovadoras
                    </span>
                    Creemos en la era digital y en<br />
                    sus grandes cambios, por eso GAF invierte en<br />
                    desarrollar nuevas tecnologías con<br />
                    funcionalidades y servicios que innoven los<br />
                    procesos de las industrias.
                  </p>
                </div>
                <div class="col-lg-4 col-sm-12">
                  <img src="/img/minticon.svg" alt="img-asesoria" width="70%" />
                </div>
              </div>
              <div class="row content-pop2">
                <div class="col-lg-4 col-sm-12">
                  <img src="/img/nubeicon.svg" alt="img-asesoria" width="70%" />
                </div>
                <div class="col-lg-8 col-sm-12">
                  <p class="text-popup pad-sec4">
                    <span class="font-weight-bold text-spring-green"
                      >Automáticas
                    </span>
                    gracias a la inteligencia<br />
                    artificial entrenamos nuestra herramienta<br />
                    para que pueda ayudarte en la toma de<br />
                    decisiones y avanzar trabajos operativos.
                  </p>
                </div>
              </div>
              <div class="row content-pop2">
                <div class="col-lg-9 col-sm-12">
                  <p class="text-popup pad-sec4">
                    <span class="font-weight-bold text-spring-green"
                      >Seguras
                    </span>
                    en GAF te ofrecemos una experiencia<br />
                    100% segura gracias a la tecnología blockchain<br />
                    que nos permite garantizar la seguridad de tus<br />
                    procesos y la información.
                  </p>
                </div>
                <div class="col-lg-3 col-sm-12">
                  <img
                    src="/img/monedafinal.svg"
                    alt="img-asesoria"
                    class="img-moneda"
                  />
                </div>
              </div>
            </b-modal>
          </div>
        </div>
        <div class="col-lg-7 col-sm-12 img-desk">
          <img src="/img/home-top-illustration.svg" class="img-fluid w-100" />
        </div>
      </div>
    </section>
    <section class="container subscriptions py-5">
      <div class="row no-gutters">
        <div class="col-lg-4 col-sm-12 pad-cuadros">
          <div class="subscription-card">
            <span class="percent">5%</span>
            <span class="text"> Suscripción <span>Mensual</span> </span>
            <small class="small-text"> *En la primera consulta </small>
          </div>
        </div>
        <div class="col-lg-4 col-sm-12 pad-cuadros">
          <div class="subscription-card">
            <span class="percent">15%</span>
            <span class="text"> Suscripción <span>Trimestral</span> </span>
            <small class="small-text"> *En la primera consulta </small>
          </div>
        </div>
        <div class="col-lg-4 col-sm-12 pad-cuadros-n">
          <div class="subscription-card featured">
            <div class="flag-wrap">
              <img class="flag" src="/img/flag-featured-pack.svg" alt="" />
              <span>Mejor Oferta</span>
            </div>
            <span class="percent">25%</span>
            <span class="text"> Suscripción <span>Anual</span> </span>
            <small class="small-text"> *En la primera consulta </small>
          </div>
        </div>
      </div>
    </section>
    <section class="container py-5 mb-5">
      <div class="row">
        <div class="col-lg-4 col-sm-12 pad-recta">
          <div class="package">
            <div class="circle-shape"></div>
            <div class="price-wrap bg-caribbean-green"></div>
            <div class="price">
              <span class="currency">$</span>
              <span class="amount">00</span>
              <span class="period">mo</span>
            </div>
            <div class="card-shape">
              <div class="package-title">
                <h3>Silver</h3>
              </div>
              <ul class="package-features">
                <li>
                  <ListIcon variant="caribbean-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="caribbean-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="caribbean-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
              </ul>
              <div class="text-center mb-5">
                <b-button variant="outline-caribbean-green package-btn">
                  Comprar
                </b-button>
              </div>
              <div class="bottom-decor bg-caribbean-green"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-12 pad-recta">
          <div class="package">
            <div class="circle-shape"></div>
            <div class="price-wrap bg-elf-green"></div>
            <div class="price">
              <span class="currency">$</span>
              <span class="amount">00</span>
              <span class="period">mo</span>
            </div>
            <div class="card-shape">
              <div class="package-title">
                <h3>Gold</h3>
              </div>
              <ul class="package-features">
                <li>
                  <ListIcon variant="elf-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="elf-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="elf-green" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
              </ul>
              <div class="text-center mb-5">
                <b-button variant="outline-elf-green package-btn">
                  Comprar
                </b-button>
              </div>
              <div class="bottom-decor bg-elf-green"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-12 pad-recta">
          <div class="package featured">
            <div class="circle-shape"></div>
            <div class="price-wrap bg-black"></div>
            <div class="price">
              <span class="currency">$</span>
              <span class="amount">00</span>
              <span class="period">mo</span>
            </div>
            <div class="card-shape">
              <div class="package-title">
                <h3>Diamond</h3>
              </div>
              <ul class="package-features">
                <li>
                  <ListIcon variant="tangerine-yellow" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="tangerine-yellow" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
                <li>
                  <ListIcon variant="tangerine-yellow" />
                  Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed
                  diam nonummy nibh.
                </li>
              </ul>
              <div class="text-center mb-5">
                <b-button variant="outline-orange package-btn">
                  Comprar
                </b-button>
              </div>
              <div class="bottom-decor bg-black"></div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="container-fluid follow-us mb-5">
      <div class="row">
        <div class="col-lg-7 img-desk">
          <img
            src="/img/home-bottom-illustration.svg"
            class="img-fluid w-100"
          />
        </div>
        <div
          class="
            col-lg-4 col-sm-12
            d-flex
            align-items-center
            justify-content-center
          "
        >
          <div class="text-center">
            <h2>¡Siguenos!</h2>
            <div class="social-media mb-3">
              <a href="">
                <FacebookIcon />
              </a>
              <a href="">
                <InstagramIcon />
              </a>

              <a href="">
                <TwitterIcon />
              </a>
              <a href="">
                <YoutubeIcon />
              </a>
            </div>
            <span class="text-big d-block font-weight-bold text-uppercase">
              Más Información
            </span>
            <span class="text-big d-block">+91-878787-12345</span>
          </div>
        </div>
      </div>
    </section>
    <div class="container-fluid sub-footer py-3">
      <div class="row">
        <div class="col-sm-12 img-final-res">
          <img
            src="/img/home-bottom-illustration.svg"
            class="img-fluid w-100"
          />
        </div>
        <div class="col-lg-12 text-center direction-desk">
          <span> Av. Lorem ipsum dolor 2045 Dolor sit Amet - Adress </span>
        </div>
        <div class="col-sm-12 text-center direction-res">
          <span class="direction-text-res">
            Av. Lorem ipsum dolor 2045<br />
            Dolor sit Amet - Adress
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  ListIcon,
  FacebookIcon,
  InstagramIcon,
  YoutubeIcon,
  TwitterIcon,
} from "../icons";

export default {
  components: {
    ListIcon,
    FacebookIcon,
    InstagramIcon,
    YoutubeIcon,
    TwitterIcon,
  },
};
</script>
