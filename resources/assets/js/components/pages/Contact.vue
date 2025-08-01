<template>
  <div>
    <section class="container-fluid follow-us mb-5">
      <div class="row">
        <div class="col-lg-6 img-desk d-flex align-items-center">
          <img src="/img/home-bottom-illustration.svg" class="img-fluid w-100" />
        </div>
        <div class="col-lg-5 col-sm-12 d-flex align-items-start justify-content-center">
          <div>
            <h5 class="contactanos text-center mb-0">¡Contáctanos!</h5>
            <div class="row">
              <div class="panel mb-3 col-md-12">
                <div class="panel-body">
                  <form @submit.prevent="onSubmit">
                    <div class="row">
                      <div class="col-6 mt-4">
                        <input placeholder="Nombres:" class="form-control"
                          :class="{ 'is-invalid': $v.form.firstName.$error }" type="text" v-model.trim="form.firstName" />
                        <div class="invalid-feedback">
                          Este es un campo obligatorio.
                        </div>
                      </div>
                      <div class="col-6 mt-4">
                        <input placeholder="Apellidos:" class="form-control"
                          :class="{ 'is-invalid': $v.form.lastName.$error }" type="text" v-model.trim="form.lastName" />
                        <div class="invalid-feedback">
                          Este es un campo obligatorio.
                        </div>
                      </div>
                      <div class="col-12 mt-4">
                        <input placeholder="Teléfono:" class="form-control"
                          :class="{ 'is-invalid': $v.form.phone.$error }" type="number" v-model.trim="form.phone" />
                        <div class="invalid-feedback">
                          Este es un campo obligatorio.
                        </div>
                      </div>
                      <div class="col-12 mt-4">
                        <input placeholder="Correo electrónico:" class="form-control"
                          :class="{ 'is-invalid': $v.form.email.$error }" type="email" v-model.trim="form.email" />
                        <div class="invalid-feedback">
                          <template v-if="!$v.form.email.required">Este es un campo obligatorio.</template>
                          <template v-if="!$v.form.email.email">El correo electrónico no es válido.</template>
                        </div>
                      </div>
                      <div class="col-12 mt-4">
                        <input placeholder="Empresa:" class="form-control"
                          :class="{ 'is-invalid': $v.form.company.$error }" type="text" v-model.trim="form.company" />
                        <div class="invalid-feedback">
                          Este es un campo obligatorio.
                        </div>
                      </div>
                      <div class="col-12 mt-4">
                        <input placeholder="Cargo:" class="form-control" type="text"
                          :class="{ 'is-invalid': $v.form.position.$error }" v-model.trim="form.position" />
                        <div class="invalid-feedback">
                          Este es un campo obligatorio.
                        </div>
                      </div>
                      <div class="col-12 mt-4">
                        <textarea name="comentario" class="form-control" placeholder="Comentarios:" cols="30" rows="4"
                          v-model.trim="form.comment"></textarea>
                      </div>
                      <div class="col-12">
                        <p class="my-3">
                          La información que proporciones a GAF Solutions se rige por los términos
                          de nuestra <a href="/politicas">Politica de privacidad</a>
                        </p>
                      </div>
                      <div class="col-12 text-center">
                        <b-button type="submit" class="px-3 btn-informacion" :disabled="isSending">
                          <b-spinner v-if="isSending" small class="mr-1" /> ¡ENVIAR!
                        </b-button>
                        <div v-if="alertProps.show" class="mt-3 alert" :class="`alert-${alertProps.variant}`"
                          role="alert">
                          {{ alertProps.message }}
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-sm-12 text-center mt-4">
          <span class="text-big d-block font-weight-bold text-uppercase"> ¡Síguenos! </span>
          <div class="social-media mt-4 mb-3">
            <a href="https://www.facebook.com/" class="icons">
              <FacebookIcon />
            </a>
            <a href="https://www.instagram.com/" class="icons">
              <InstagramIcon />
            </a>

            <a href="https://twitter.com/" class="icons">
              <TwitterIcon />
            </a>
            <a href="https://www.youtube.com/" class="icons">
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
import { email, required } from 'vuelidate/lib/validators'

export default {
  data() {
    return {
      form: {
        firstName: '',
        lastName: '',
        phone: '',
        email: '',
        company: '',
        position: '',
        comment: ''
      },
      isSending: false,
      alertProps: {
        show: false,
        variant: null,
        message: ''
      },
    }
  },
  validations: {
    form: {
      firstName: {
        required
      },
      lastName: {
        required
      },
      phone: {
        required
      },
      email: {
        required,
        email
      },
      company: {
        required
      },
      position: {
        required
      },
    }
  },
  components: {
    ListIcon,
    FacebookIcon,
    InstagramIcon,
    YoutubeIcon,
    TwitterIcon
  },
  methods: {
    resetForm() {
      this.form = {
        firstName: '',
        lastName: '',
        phone: '',
        email: '',
        company: '',
        position: '',
        comment: ''
      };

      this.$nextTick(() => {
        this.$v.$reset();
      });
    },
    onSubmit() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }

      const params = this.form;
      this.isSending = true;

      axios
        .post('/contact', params)
        .then(res => {
          this.alertProps = {
            show: true,
            variant: 'success',
            message: res.data.message
          };
        })
        .catch(error => {
          this.alertProps = {
            show: true,
            variant: 'danger',
            message: error.response.data.message
          };
        })
        .finally(() => {
          this.isSending = false;

          this.resetForm();

          setTimeout(() => {
            this.alertProps = {
              show: false,
              variant: null,
              message: ''
            };
          }, 5000);
        });
    }
  }
};
</script>

<style>
.contactanos {
  font-family: BillionDreams;
  font-style: normal;
  font-weight: normal;
  font-size: 5vw;
  color: #0cedb0;
}

.btn-informacion:hover {
  background-color: black;
  color: rgb(9, 186, 139);
}

.btn-informacion {
  background-color: black;
  color: white;
  min-width: 220px;
}
</style>

<style lang="scss" scoped>
.form-control {
  border-radius: 10px;

  &:not(.is-invalid) {
    border-color: #000000;
  }
}
.icons:hover{
  opacity: 0.5;
}
</style>

