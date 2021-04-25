<template>
  <v-container class="cardBg mb-12 mb-sm-0" col-md-6>
    <h2>REJESTRACJA</h2>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <form class="mt-3 mb-2" @submit.prevent="submit">
        <validation-provider
          v-slot="{ errors }"
          name="Nazwa użytkownika"
          rules="required|min:3"
        >
          <v-text-field
            v-model="form.name"
            :error-messages="errors"
            label="Nazwa użytkownika"
            prepend-inner-icon="mdi-account"
            required
            filled
          ></v-text-field>
        </validation-provider>
        <validation-provider
          v-slot="{ errors }"
          name="E-mail"
          rules="required|email"
        >
          <v-text-field
            v-model="form.email"
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
          rules="required|min:8|upperCase|lowerCase|num"
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
        <validation-provider
          v-slot="{ errors }"
          name="Powtórz hasło"
          rules="required|confirmed:password"
        >
          <v-text-field
            v-model="form.password_confirmation"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show2 ? 'text' : 'password'"
            :error-messages="errors"
            label="Powtórz hasło"
            prepend-inner-icon="mdi-key-variant"
            required
            filled
            @click:append="show2 = !show2"
          ></v-text-field>
        </validation-provider>
        <p v-if="validationErrors['input.email']" class="error--text">
          {{ validationErrors['input.email'][0] }}
        </p>
        <v-btn class="mr-4" type="submit" :disabled="invalid">
          Zarejestruj się
        </v-btn>
        <NuxtLink to="/login">
          <span class="mt-3 d-inline-flex">Masz już konto? Zaloguj się!</span>
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
      show2: false,
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      validationErrors: '',
    }
  },
  methods: {
    async submit() {
      await this.$apollo
        .mutate({
          mutation: gql`
            mutation createUser($input: RegisterInput) {
              register(input: $input) {
                tokens {
                  user {
                    id
                    name
                  }
                }
                status
              }
            }
          `,
          variables: {
            input: this.form,
          },
        })
        .then(() => {
          this.$router.push('/login')
          this.$store.commit('setSnackbar', true)
          this.$store.commit(
            'setSnackbarText',
            'Rejestracja przebiegła poprawnie'
          )
        })
        .catch((error) => {
          const { graphQLErrors } = error
          if (graphQLErrors[0].extensions.category === 'validation') {
            this.validationErrors = graphQLErrors[0].extensions.validation
          } else console.log(error)
        })
    },
  },
}
</script>
