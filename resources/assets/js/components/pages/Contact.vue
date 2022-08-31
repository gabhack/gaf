<template>
  <div>
    <section class="container-fluid follow-us mb-5">
      <div class="row">
        <div class="col-lg-7 img-desk">
          <img src="/img/home-bottom-illustration.svg" class="img-fluid w-100" />
        </div>
        <div class="col-lg-4 col-sm-12 d-flex align-items-center justify-content-center">
          <div class="text-center">
            <h2>¡Contactanos!</h2>
            <div class="row">
              <div class="panel mb-3 col-md-12">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-6">
                      <b class="panel-label">NOMBRES:</b>
                      <input required class="form-control text-center" type="text" />
                    </div>
                    <div class="col-6">
                      <b class="panel-label">APELLIDOS:</b>
                      <input required class="form-control text-center" type="text" />
                    </div>
                    <div class="col-6">
                      <b class="panel-label">TELÉFONO:</b>
                      <input required class="form-control text-center" type="text" />
                    </div>
                    <div class="col-6">
                      <b class="panel-label">CORREO ELECTRÓNICO:</b>
                      <input required class="form-control text-center" type="text" />
                    </div>
                    <div class="col-12">
                      <b class="panel-label">¿QUÉ QUIERES SABER?:</b>
                      <input required class="form-control text-center" type="text" />
                    </div>
                    <div class="col-12 mt-4">
                      <b-button variant="black-pearl" type="button" class="px-3">ENVIAR</b-button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-sm-12 text-center mt-4">
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
          <span class="text-big d-block font-weight-bold text-uppercase"> Más Información </span>
          <span class="text-big d-block">+57 300 425 3256</span>
          <span class="text-medium d-block">contacto@gafsolutions.co</span>
        </div>
      </div>
    </section>
    <div class="container-fluid sub-footer py-3">
      <div class="row">
        <div class="col-sm-12 img-final-res">
          <img src="/img/home-bottom-illustration.svg" class="img-fluid w-100" />
        </div>
        <div class="col-lg-12 text-center direction-desk">
          <span> Cra. 100 #11-60. Oficina 605, Torre Farallones </span>
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
import { ListIcon, FacebookIcon, InstagramIcon, YoutubeIcon, TwitterIcon } from '../icons';

export default {
  components: {
    ListIcon,
    FacebookIcon,
    InstagramIcon,
    YoutubeIcon,
    TwitterIcon
  }
};
</script>
