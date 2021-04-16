<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      :clipped="clipped"
      temporary
      fixed
      app
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
      <v-banner></v-banner>
      <v-switch
        v-model="darkMode"
        class="ml-10"
        inset
        label="Tryb ciemny"
        prepend-icon="mdi-moon-waning-crescent"
        @change="toogleTheme"
      />
    </v-navigation-drawer>
    <v-app-bar fixed app>
      <v-app-bar-nav-icon
        class="hidden-md-and-up"
        @click.stop="drawer = !drawer"
      />
      <!-- <v-btn icon @click.stop="miniVariant = !miniVariant">
        <v-icon>mdi-{{ `chevron-${miniVariant ? 'right' : 'left'}` }}</v-icon>
      </v-btn>
      <v-btn icon @click.stop="clipped = !clipped">
        <v-icon>mdi-application</v-icon>
      </v-btn>
      <v-btn icon @click.stop="fixed = !fixed">
        <v-icon>mdi-minus</v-icon>
      </v-btn> -->
      <!-- <NuxtLink to="/" class="text-decoration-none"> -->
      <v-toolbar-title v-text="title" class="text-decoration-none pl-0" />
      <!-- </NuxtLink> -->
      <!-- <v-icon class="ml-5 mr-5">{{ 'mdi-soccer' }}</v-icon> -->
      <v-toolbar-items class="ml-5 mr-5 hidden-sm-and-down">
        <v-btn v-for="(item, i) in items" :key="i" :to="item.to" router exact>
          {{ item.title }}
          <v-icon class="ml-2">{{ item.icon }}</v-icon>
        </v-btn>
      </v-toolbar-items>
      <v-spacer></v-spacer>
      <v-switch
        v-model="darkMode"
        class="mt-6 mr-1 hidden-xs-only"
        inset
        prepend-icon="mdi-moon-waning-crescent"
        @change="toogleTheme"
      />
      <span v-if="!me" class="d-inline-flex">
        <v-btn
          class="mr-3 hidden-md-and-down rounded-lg"
          color="primary"
          to="/login"
          exact
        >
          Zaloguj się
        </v-btn>
        <v-btn
          class="hidden-md-and-down rounded-lg"
          color="secondary"
          to="/register"
          exact
        >
          Zarejestruj się
        </v-btn>
        <v-btn class="hidden-lg-and-up" fab small to="/login" exact>
          <v-icon>{{ 'mdi-account-circle' }}</v-icon>
        </v-btn>
      </span>
      <span v-else>
        <v-menu bottom min-width="200px" rounded offset-y>
          <template v-slot:activator="{ on }">
            <v-btn fab small v-on="on">
              <v-icon>{{ 'mdi-account-circle' }}</v-icon>
            </v-btn>
          </template>
          <v-card>
            <v-list-item-content class="justify-center">
              <div class="mx-auto text-center">
                <v-icon class="mb-2">{{ 'mdi-account' }}</v-icon>
                <h3>{{ me.name }}</h3>
                <v-divider class="my-2"></v-divider>
                <v-btn depressed text to="/profile" exact>Moje konto</v-btn>
                <v-divider class="my-2"></v-divider>
                <v-btn depressed text @click="onLogout">Wyloguj się</v-btn>
              </div>
            </v-list-item-content>
          </v-card>
        </v-menu>
      </span>
      <!-- <v-spacer />
      <v-btn icon @click.stop="rightDrawer = !rightDrawer">
        <v-icon>mdi-menu</v-icon>
      </v-btn> -->
    </v-app-bar>
    <!-- <v-app-bar app color="#414141" dark height="80">
      <v-app-bar-nav-icon></v-app-bar-nav-icon>
      <v-toolbar-title>Football Stats App</v-toolbar-title>
      <v-img
        src="http://127.0.0.1:8887/trophy.svg"
        max-height="50%"
        max-width="50px"
        contain
      />
      <v-img
        src="http://127.0.0.1:8887/football.svg"
        max-height="50%"
        max-width="50px"
        contain
      />
      <v-spacer></v-spacer>
      <v-btn>Zaloguj się</v-btn>
      <v-btn>Zarejestruj się</v-btn>
      <template v-slot:extension>
        <v-tabs align-with-title>
          <v-tab>Terminarz</v-tab>
          <v-tab>Rozgrywki</v-tab>
          <v-tab>Naj...</v-tab>
          <v-tab>Ulubione</v-tab>
        </v-tabs>
      </template>
    </v-app-bar> -->
    <v-main>
      <v-container>
        <Snackbar />
        <nuxt />
      </v-container>
    </v-main>
    <!-- <v-navigation-drawer v-model="rightDrawer" :right="right" temporary fixed>
      <v-list>
        <v-list-item @click.native="right = !right">
          <v-list-item-action>
            <v-icon light> mdi-repeat </v-icon>
          </v-list-item-action>
          <v-list-item-title>Switch drawer (click me)</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer> -->
    <v-footer :absolute="!fixed" app>
      <span>Football Stats App &copy; {{ new Date().getFullYear() }}</span>
      <v-spacer></v-spacer>
      <div>
        Icons made by
        <a href="https://www.freepik.com" title="Freepik">Freepik</a>
        from
        <a href="https://www.flaticon.com/" title="Flaticon">
          www.flaticon.com
        </a>
      </div>
    </v-footer>
  </v-app>
</template>

<script>
import gql from 'graphql-tag'
import Snackbar from '../components/Snackbar.vue'
export default {
  components: { Snackbar },
  data() {
    return {
      darkMode: this.$vuetify.theme.dark,
      clipped: false,
      drawer: false,
      fixed: false,
      me: null,
      // info: '',
      items: [
        // {
        //   icon: 'mdi-home',
        //   title: 'Strona Główna',
        //   to: '/',
        // },
        {
          icon: 'mdi-calendar',
          title: 'Wyniki/Terminarz',
          to: '/matches',
        },
        {
          icon: 'mdi-strategy',
          title: 'Rozgrywki',
          to: '/competitions',
        },
        {
          icon: 'mdi-podium',
          title: 'Najlepsi',
          to: '/best',
        },
        // {
        //   icon: 'mdi-star',
        //   title: 'Ulubione',
        //   to: '/favourite',
        // },
      ],
      // right: true,
      // rightDrawer: false,
      title: 'Football Stats App',
    }
  },
  methods: {
    toogleTheme() {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark
    },
    async onLogout() {
      await this.$apollo
        .mutate({
          mutation: gql`
            mutation logoutUser {
              logout {
                status
                message
              }
            }
          `,
        })
        .then(() => {
          // this.info = data.data.logout.message
          this.$apolloHelpers.onLogout()
          this.$router.push('/matches')
          this.$store.commit('setSnackbar', true)
          this.$store.commit('setSnackbarText', 'Wylogowano poprawnie')
        })
    },
  },
  apollo: {
    me: gql`
      query {
        me {
          id
          name
          email
        }
      }
    `,
  },
}
</script>
