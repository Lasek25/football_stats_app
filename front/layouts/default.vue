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
      <NuxtLink to="/" class="text-decoration-none white--text">
        <v-toolbar-title v-text="title" />
      </NuxtLink>
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
        class="mt-6 mr-3 hidden-xs-only"
        inset
        prepend-icon="mdi-moon-waning-crescent"
        @change="toogleTheme"
      />
      <!-- <v-btn @click="toogleTheme">zmien</v-btn> -->
      <v-btn class="mr-3 hidden-md-and-down rounded-lg" color="primary">
        Zaloguj się
      </v-btn>
      <v-btn class="hidden-md-and-down rounded-lg" color="secondary">
        Zarejestruj się
      </v-btn>
      <v-btn class="hidden-lg-and-up" fab small>
        <v-icon>{{ 'mdi-account-circle' }}</v-icon>
      </v-btn>
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
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      darkMode: this.$vuetify.theme.dark,
      clipped: false,
      drawer: false,
      fixed: false,
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
          icon: 'mdi-soccer-field',
          title: 'Rozgrywki',
          to: '/competitions',
        },
        {
          icon: 'mdi-podium',
          title: 'Najlepsi',
          to: '/best',
        },
        {
          icon: 'mdi-star',
          title: 'Ulubione',
          to: '/favourite',
        },
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
  },
}
</script>
