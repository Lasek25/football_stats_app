<template>
  <v-container class="cardBg mb-12 mb-sm-0" col-md-6>
    <h2>MOJE KONTO</h2>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <v-form class="mt-3 mb-3" @submit.prevent="updateUser">
        <h3>Dane użytkownika</h3>
        <v-divider class="mb-3"></v-divider>
        <validation-provider
          v-slot="{ errors }"
          name="Nazwa użytkownika"
          rules="required|min:3"
        >
          <v-text-field
            v-model="meTmp.name"
            :error-messages="errors"
            label="Nazwa użytkownika"
            prepend-inner-icon="mdi-account"
            required
            filled
          ></v-text-field>
        </validation-provider>
        <v-text-field
          v-model="meTmp.email"
          label="E-mail"
          prepend-inner-icon="mdi-email"
          disabled
          filled
        ></v-text-field>
        <v-btn type="submit" :disabled="invalid">Zapisz zmiany</v-btn>
      </v-form>
    </validation-observer>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <v-form class="mt-8 mb-3" @submit.prevent="updatePassword">
        <h3>Zmień hasło</h3>
        <v-divider class="mb-3"></v-divider>
        <validation-provider
          v-slot="{ errors }"
          name="Obecne hasło"
          rules="required"
        >
          <v-text-field
            v-model="passwordForm.old_password"
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            :error-messages="errors"
            label="Obecne hasło"
            prepend-inner-icon="mdi-key-variant"
            required
            filled
            @click:append="show1 = !show1"
          ></v-text-field>
        </validation-provider>
        <validation-provider
          v-if="passwordForm.old_password"
          v-slot="{ errors }"
          name="Nowe hasło"
          rules="required|min:8|upperCase|lowerCase|num"
          vid="password"
        >
          <v-text-field
            v-model="passwordForm.password"
            :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show2 ? 'text' : 'password'"
            :error-messages="errors"
            label="Nowe hasło"
            prepend-inner-icon="mdi-key-variant"
            required
            filled
            @click:append="show2 = !show2"
          ></v-text-field>
        </validation-provider>
        <validation-provider
          v-if="passwordForm.old_password && passwordForm.password"
          v-slot="{ errors }"
          name="Powtórz nowe hasło"
          rules="required|confirmed:password"
        >
          <v-text-field
            v-model="passwordForm.password_confirmation"
            :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show3 ? 'text' : 'password'"
            :error-messages="errors"
            label="Powtórz nowe hasło"
            prepend-inner-icon="mdi-key-variant"
            required
            filled
            @click:append="show3 = !show3"
          ></v-text-field>
        </validation-provider>
        <p v-if="validationErrors" class="error--text">
          {{ validationErrors }}
        </p>
        <v-btn type="submit" :disabled="invalid">Zmień hasło</v-btn>
      </v-form>
    </validation-observer>
  </v-container>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import gql from 'graphql-tag'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  middleware: 'auth',
  data() {
    return {
      meTmp: null,
      validationErrors: '',
      show1: false,
      show2: false,
      show3: false,
      passwordForm: {
        old_password: '',
        password: '',
        password_confirmation: '',
      },
    }
  },
  created() {
    this.meTmp = { ...this.me }
  },
  methods: {
    reset() {
      this.validationErrors = ''
      this.passwordForm = {
        old_password: '',
        password: '',
        password_confirmation: '',
      }
      this.$nextTick(() => this.$refs.observer.reset())
    },
    setShow() {
      this.show1 = false
      this.show2 = false
      this.show3 = false
    },
    async updatePassword() {
      await this.$apollo
        .mutate({
          mutation: gql`
            mutation updateUserPassword($input: UpdatePassword!) {
              updatePassword(input: $input) {
                status
                message
              }
            }
          `,
          variables: {
            input: this.passwordForm,
          },
        })
        .then((data) => {
          this.$store.commit(
            'setSnackbarText',
            data.data.updatePassword.message
          )
          this.$store.commit('setSnackbar', true)
          this.reset()
          this.setShow()
        })
        .catch((error) => {
          this.setShow()
          const { graphQLErrors } = error
          if (graphQLErrors[0].extensions.category === 'validation') {
            this.validationErrors = graphQLErrors[0].extensions.errors.password
          } else console.log(error)
        })
    },
    async updateUser() {
      await this.$apollo
        .mutate({
          mutation: gql`
            mutation updateUserProfile($name: String!) {
              updateUser(name: $name) {
                status
                message
              }
            }
          `,
          variables: {
            name: this.meTmp.name,
          },
        })
        .then((data) => {
          this.$store.commit('setSnackbarText', data.data.updateUser.message)
          this.$store.commit('setSnackbar', true)
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
