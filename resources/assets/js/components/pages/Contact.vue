<template>
  <div>
    <section class="container-fluid follow-us mb-5">
      <div class="row">
        <div class="col-lg-6 img-desk d-flex align-items-center">
          <img src="/img/home-bottom-illustration.svg" class="img-fluid w-100" />
        </div>
        <div class="col-lg-5 col-sm-12 d-flex align-items-start justify-content-center">
          <div>
            <h5 class="contactanos text-center mb-0">¡Contactanos!</h5>
            <div class="row">
              <div class="panel mb-3 col-md-12">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-6 mt-4">
                      <input required  placeholder="Nombres:" class="form-control1" type="text" />
                    </div>
                    <div class="col-6 mt-4">
                      <input required placeholder="Apellidos:" class="form-control1" type="text" />
                    </div>
                    <div class="col-12 mt-4">
                      <input required placeholder="Teléfono:" class="form-control1" type="number" />
                    </div>
                    <div class="col-12 mt-4">
                      <input required placeholder="Correo electrónico:" class="form-control1" type="email" />
                    </div>
                    <div class="col-12 mt-4">
                      <input required placeholder="Empresa:" class="form-control1" type="text" />
                    </div>
                    <div class="col-12 mt-4">
                      <input required placeholder="Cargo:" class="form-control1" type="text" />
                    </div>
                    <div class="col-12 mt-4">
                      <textarea name="comentario" class="form-control2" placeholder="Comentarios:" id="" cols="30" rows="10"></textarea>
                    </div>
                    <div class="col-12">
                      <p class="my-3">La información que proporciones a GAF Solutions se rige por los términos
                        de nuestra <a href="">Politica de privacidad</a> </p>
                    </div>
                    <div class="col-12 text-center">
                      <b-button type="button" class="px-3 btn-informacion">¡ENVIAR!</b-button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-sm-12 text-center mt-4">
          
          <span class="text-big d-block font-weight-bold text-uppercase"> ¡Síguenos! </span>
          <div class="social-media mt-4 mb-3">
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
          <!-- <span class="text-big d-block">Tel. 3183109067</span>
          <span class="text-medium d-block">info@gafsolutions.co</span> -->
        </div>
      </div>
    </section>
    <!-- <div class="container-fluid sub-footer py-3">
      <div class="row">
        <div class="col-sm-12 img-final-res">
          <img src="/img/home-bottom-illustration.svg" class="img-fluid w-100" />
        </div>
        <div class="col-lg-12 text-center direction-desk">
          <span> Cra. 100 #11-60 HTC Torre Farallones Oficina 605 </span>
        </div>
        <div class="col-sm-12 text-center direction-res">
          <span class="direction-text-res">
            Cra. 100 #11-60 HTC<br />
            HTC Torre Farallones Oficina 605
          </span>
        </div>
      </div>
    </div> -->
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
<style>
.contactanos{
  font-family: BillionDreams;
  font-style: normal;
  font-weight: normal;
  font-size: 5vw;
  color: #0cedb0;
}

.form-control1{
  height: 36px;
  width: 100%;
  border: 1px solid black;
  border-radius: 10px;
  text-indent: 10px;
}
.form-control2{
  height: 100px;
  width: 100%;
  text-indent: 10px;
  border: 1px solid black;
  border-radius: 10px;
  vertical-align: text-top;
}
.form-control1::placeholder, .form-control2::placeholder{
  display: flex-start;
}
.btn-informacion:hover{
  background-color: black;
  color: rgb(9, 186, 139);
}
.btn-informacion{
  background-color: black;
  color: white;
  min-width: 220px;
}

.form-control2::placeholder{
  position: absolute;
  top:0;
    }
</style>
