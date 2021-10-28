<template>
  <header>
    <b-navbar toggleable="lg" type="light" variant="white" class="py-3x px-5x">
      <div class="container">
        <b-navbar-brand href="#" class="mr-5">
          <img src="/img/logo-gaf.svg" alt="Logo GAF" width="60px" />
        </b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <div>
          <b-button variant="black-pearl" class="mr-2">
            <span>Personas</span>
            <PersonsIcon />
          </b-button>
          <b-button variant="spring-green">
            <span>Empresas</span>
            <CompaniesIcon />
          </b-button>
        </div>

        <b-collapse id="nav-collapse" is-nav>
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
        </b-collapse>
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
