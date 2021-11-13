<template>
  <header>
    <b-navbar toggleable="lg" type="light" variant="white" class="py-3x px-5x">
      <div class="container">
        <b-navbar-brand href="/welcome" class="mr-5">
          <img src="/img/logo-gaf.svg" alt="Logo GAF" width="60px" />
        </b-navbar-brand>

        <div class="icons-desktop">
          <b-button variant="black-pearl" class="mr-2" href="/welcome">
            <span>Personas</span>
            <PersonsIcon />
          </b-button>
          <b-dropdown variant="spring-green">
            <template #button-content>
              Empresas
              <CompaniesIcon />
            </template>
            <b-dropdown-item href="/amipersonas">Ami</b-dropdown-item>
            <b-dropdown-item href="/hego">Hego</b-dropdown-item>
          </b-dropdown>
        </div>
        <div class="icons-responsie">
          <b-button variant="black-pearl" class="mr-2">
            <PersonsIcon />
          </b-button>
          <b-button variant="spring-green">
            <CompaniesIcon />
          </b-button>
        </div>

        <div class="popup-res">
          <b-button v-b-modal.modal-1>Asesoria</b-button>

          <b-modal id="modal-1">
            <div class="row">
              <div class="col-6 pad-img">
                <img
                  src="/img/asesoriaimg.svg"
                  alt="img-asesoria"
                  width="84%"
                />
              </div>
              <div class="col-6 content-pop1">
                <span class="text-big-popup d-block text-uppercase">
                  Solicíta
                </span>
                <p class="fancy-text">asesoría</p>

                <b-button
                  variant="spring-green"
                  class="btn-more-pop1 font-weight-bold"
                >
                  CLIC AQUÍ
                </b-button>
              </div>
            </div>
          </b-modal>
        </div>

        <b-navbar-nav class="ml-auto">
          <template v-for="link in menuLinks">
            <b-nav-item
              class="text-black-pearl"
              :href="link.url"
              :key="`item-${link.label}`"
            >
              {{ link.label }}
            </b-nav-item>
          </template>
          <b-nav-item-dropdown text="Menu" right>
            <b-dropdown-item href="#">EN</b-dropdown-item>
            <b-dropdown-item href="#">ES</b-dropdown-item>
            <b-dropdown-item href="#">RU</b-dropdown-item>
            <b-dropdown-item href="#">FA</b-dropdown-item>
          </b-nav-item-dropdown>
        </b-navbar-nav>
      </div>
    </b-navbar>
  </header>
</template>

<script>
import { CompaniesIcon, PersonsIcon } from "./icons";

export default {
  data() {
    return {
      menuLinks: [
        { label: "Regístrate", url: "/" },
        { label: "Ingreso", url: "/login" },
      ],
    };
  },
  components: {
    CompaniesIcon,
    PersonsIcon,
  },
};
</script>

<style lang="scss">
@import "../../sass/colors";

header {
  position: sticky;
  top: 0;
  z-index: 5;
}

.dropdown-menu:focus-visible {
  outline: none !important;
}
.dropdown-toggle:focus-visible {
  outline: none !important;
}
//Popup-styles

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  width: 744px;
  height: 432px;
  pointer-events: auto;
  background-color: #1a2e35;
}
.modal-dialog {
  max-width: 744px !important;
}
.modal-header {
  border-bottom: none !important;
}
.modal-footer {
  display: none !important;
}
.text-big-popup {
  font-size: 45px;
  line-height: 1.3;
  font-weight: 900;
  color: #0cedb0;
}
.content-pop1 {
  text-align: center;
  margin-top: 13%;
}
.pad-img {
  padding: 0 !important;
}
.close {
  float: right;
  font-size: 1.8rem;
  font-weight: 700;
  line-height: 1;
  color: #ffffff;
  text-shadow: 0 1px 0 #ffffff;
  opacity: 1.5;
}
.fancy-text {
  font-family: BillionDreams;
  font-size: 130px;
  font-weight: normal;
  line-height: 0.6;
  display: inline-block;
  color: #ffffff;
}
.btn-more-pop1 {
  max-width: 245px;
  width: 100%;
}

//Fin popup-styles

// Responsive

@media (max-width: 600px) {
  .icons-responsie {
    display: block !important;
  }
  .icons-desktop {
    display: none !important;
  }
  .menu-desktop {
    display: none !important;
  }
  .menu-responsive {
    display: block !important;
  }
  .popup-res {
    display: none !important;
  }
}
.popup-res {
  display: block;
}
.menu-responsive {
  display: none;
}
.icons-responsie {
  display: none;
}
.icons-desktop {
  display: block;
}
.menu-desktop {
  display: block;
}

.navbar {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);

  .btn {
    border-radius: 20px;
    padding-left: 1.3rem;
    padding-right: 1.3rem;

    span {
      margin-right: 5px;
      display: inline-block;
    }
  }
}

.nav-item {
  .nav-link {
    line-height: 21px;

    &:hover {
      font-weight: 800;
    }
  }
}

.btn-black-pearl {
  color: #ffffff;

  svg {
    fill: #ffffff;
  }
}

.btn-spring-green {
  color: $black-pearl;

  svg {
    fill: $black-pearl;
  }

  &:hover {
    svg {
      fill: #ffffff;
    }
  }
}
</style>
