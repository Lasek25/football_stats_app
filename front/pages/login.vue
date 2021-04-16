<template>
  <v-container class="cardBg" col-md-6>
    <h2>LOGOWANIE</h2>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <form class="mt-3" @submit.prevent="submit">
        <validation-provider
          v-slot="{ errors }"
          name="E-mail"
          rules="required|email"
        >
          <v-text-field
            v-model="form.username"
            :error-messages="errors"
            label="E-mail"
            prepend-inner-icon="mdi-email"
            required
            filled
          ></v-text-field>
        </validation-provider>
        <validation-provider
          v-slot="{ errors }"
          name="Hasło"
          rules="required"
          vid="password"
        >
          <v-text-field
            v-model="form.password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            :error-messages="errors"
            label="Hasło"
            prepend-inner-icon="mdi-key-variant"
            required
            filled
            @click:append="show1 = !show1"
          ></v-text-field>
        </validation-provider>
        <p class="error--text">
          {{ validationErrors }}
        </p>
        <v-btn class="mr-4" type="submit" :disabled="invalid">
          Zaloguj się
        </v-btn>
        <NuxtLink to="/register">
          <span class="mt-3 d-inline-flex">
            Nie masz konta? Zarejestruj się!
          </span>
        </NuxtLink>
      </form>
    </validation-observer>
  </v-container>
</template>

<script>
import gql from 'graphql-tag'
import { ValidationObserver, ValidationProvider } from 'vee-validate'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      show1: false,
      form: {
        username: '',
        password: '',
      },
      validationErrors: '',
    }
  },
  methods: {
    // async login() {
    //   try {
    //     const data = await this.$apollo.mutate({
    //       mutation: gql`
    //         mutation loginUser($input: LoginInput) {
    //           login(input: $input) {
    //             access_token
    //             user {
    //               id
    //               email
    //             }
    //           }
    //         }
    //       `,
    //       variables: {
    //         input: this.form,
    //       },
    //     })
    //     // set the jwt to the this.$apolloHelpers.onLogin
    //     await this.$apolloHelpers.onLogin(data.data.login.access_token)
    //     console.log(data.data.login.access_token)
    //   } catch (e) {
    //     console.error(e)
    //   }
    // },
    async submit() {
      await this.$apollo
        .mutate({
          mutation: gql`
            mutation loginUser($input: LoginInput) {
              login(input: $input) {
                access_token
                user {
                  id
                  email
                }
              }
            }
          `,
          variables: {
            input: this.form,
          },
        })
        .then((data) => {
          console.log(data)
          this.$router.push('/matches')
          this.$apolloHelpers.onLogin(data.data.login.access_token)
          this.$store.commit('setSnackbar', true)
          this.$store.commit(
            'setSnackbarText',
            'Logowanie przebiegło poprawnie'
          )
        })
        .catch((error) => {
          const { graphQLErrors } = error
          if (graphQLErrors[0].extensions.category === 'authentication') {
            this.validationErrors = graphQLErrors[0].extensions.reason
          } else console.log(error)
        })
    },
  },
}
</script>
