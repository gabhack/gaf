<template>
  <div>
    <section class="container-fluid mb-5">
      <div class="row">
        <div class="col-6">
          <img src="/img/amiPersonas1.svg" class="img-fluid w-100" />
        </div>
        <div class="col-6 d-flex align-items-center">
          <div class="pr-md-5 container-sections">
            <p class="font-weight-bold text-spring-green text-big-personas">
              AMI
            </p>
            <p class="text-ami font-weight-bold">
              <span class="text-spring-green mb-5">Analisis </span>
              de Mercado
              <span class="text-spring-green mb-5"> Inteligente</span>
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="container-fluid mb-5">
      <div class="row">
        <div class="col-7 d-flex align-items-center">
          <div class="pr-md-5 container-sections">
            <p class="first-text">CON NUESTRO SISTEMA</p>
            <p class="text-medium font-weight-bold">
              <span class="text-spring-green mb-5">Analisis para </span>
              aprobación de crédito
            </p>
            <p class="medium-text">
              JAMAS VOLVERAS APROBAR TUS CREDITOS COMO ANTES, con nuestro
              sistema<br />
              inteligente podrás mejorar tus resultados, optimizar tiempos de
              respuesta,<br />
              garantizar la vida de tu cartera. Recuerda que AMI elimina hasta
              en un 90%<br />
              los errores operativos
            </p>
            <b-button variant="black" class="btn-more">
              Más Información
            </b-button>
          </div>
        </div>
        <div class="col-4">
          <img src="/img/amiCards.svg" class="img-fluid w-100" />
        </div>
      </div>
    </section>
    <section class="container-fluid mb-5">
      <div class="row">
        <div class="col-6">
          <img src="/img/amiPersonas2.svg" class="img-fluid w-100" />
        </div>
        <div class="col-6 d-flex align-items-center">
          <div class="pr-md-5 container-sections">
            <p class="first-text">CON NUESTRO PROCESO</p>
            <p class="text-medium font-weight-bold">
              <span class="text-spring-green mb-5">Recuperación </span>
              de cartera
            </p>
            <p class="medium-text">
              ¡Tu cartera al día! controla tu cartera las 24 horas del día y los
              7 días de la <br />semana,sabemos lo importante que es para ti por
              eso diseñamos nuestra<br />
              herramienta mejorando los procesos y rediseñando las estrategias
              de<br />
              cobranza, Ya no es necesario esperar al cierre de mes para tomar
              decisiones o<br />
              prevenir las situaciones que afectan el desarrollo de tu cartera.
            </p>
            <b-button variant="black" class="btn-more">
              Más Información
            </b-button>
          </div>
        </div>
      </div>
    </section>
    <section class="">
      <div class="row subscription-card featured">
        <div class="col-12">
          <div class="container-sections">
            <p class="withe-font">
              DESCUBRE NUEVOS CLIENTES CON NUESTRO SISTEMA DE
            </p>
          </div>
        </div>
      </div>
    </section>
    <section class="container-fluid mb-5">
      <div class="row">
        <div class="col-12 d-flex align-items-center">
          <div class="pr-md-5 container-sections">
            <p class="text-medium font-weight-bold mt-3">
              <span class="text-spring-green mb-5">Prospección </span>
              de Mercado
            </p>
            <p>
              Conocemos millones de clientes nuevos para ti, de acuerdo a tu
              política de crédito<br />
              analizaremos los requerimientos del mercado y diseñamos la mejor
              estrategia de venta.<br />
              Podrás desarrollar diferentes estrategias como:
            </p>
            <b-button variant="spring-green" class="btn-more">
              Más Información
            </b-button>
          </div>
        </div>
      </div>
    </section>
    <section class="container py-5 mb-5">
      <div class="row">
        <div class="col-3 col-icons">
          <img src="/img/IconoAmi1.png" class="img-fluid w-40 icon-line" />
          <p class="font-weight-bold text-line">
            ESTRATEGIA DE <br />
            <span class="text-spring-green mb-5"
              >PENETRACIÓN DE <br />MERCADO</span
            >
          </p>
        </div>
        <div class="col-3 col-icons">
          <img src="/img/IconoAmi2.png" class="img-fluid w-40 icon-line" />
          <p class="font-weight-bold text-line">
            ESTRATEGIA DE <br />
            <span class="text-spring-green mb-5"
              >DESARROLLO DE <br />MERCADO</span
            >
          </p>
        </div>
        <div class="col-3 col-icons">
          <img src="/img/IconoAmi3.png" class="img-fluid w-40 icon-line" />
          <p class="font-weight-bold text-line">
            ESTRATEGIA DE <br />
            <span class="text-spring-green mb-5"
              >DESARROLLO DE <br />PRODUCTO</span
            >
          </p>
        </div>
        <div class="col-3 col-icons">
          <img src="/img/IconoAmi4.png" class="img-fluid w-40 icon-line" />
          <p class="font-weight-bold text-line">
            ESTRATEGIA DE <br />
            <span class="text-spring-green mb-5">DIVERSIFICACIÓN</span>
          </p>
        </div>
      </div>
    </section>
    <section class="container-fluid follow-us mb-5">
      <div class="row">
        <div class="col-6 d-flex align-items-center justify-content-center">
          <div class="text-center">
            <h2 class="text-black-pearl text-cursi">
              con<span class="text-spring-green black-pearl text-pc"
                >A<span class="text-black-pearl">M</span>I</span
              >
            </h2>
            <span class="black-pearl font-weight-bold text-final1">
              siempre darás <br />
              <span class="text-spring-green text-final2">EN EL BLANCO</span>
            </span>
          </div>
        </div>
        <div class="col-6">
          <img src="/img/amiPersonas3.svg" class="img-fluid w-90" />
        </div>
      </div>
    </section>
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
  name: "ami-personas",
  components: {
    ListIcon,
    FacebookIcon,
    InstagramIcon,
    YoutubeIcon,
    TwitterIcon,
  },
};
</script>
