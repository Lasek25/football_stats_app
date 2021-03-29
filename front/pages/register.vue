<template>
  <v-container class="cardBg">
    <h2>REJESTRACJA</h2>
    <validation-observer ref="observer" v-slot="{ invalid }">
      <form @submit.prevent="submit">
        <validation-provider
          v-slot="{ errors }"
          name="Nazwa użytkownika"
          rules="required|min:3"
        >
          <v-text-field
            v-model="name"
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
            v-model="email"
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
            v-model="password"
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
            v-model="password_confirmation"
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
        <v-btn class="mr-4" type="submit" :disabled="invalid">
          Zarejestruj się
        </v-btn>
      </form>
    </validation-observer>
  </v-container>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
// import axios from 'axios'

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      show1: false,
      show2: false,
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    }
  },
  methods: {
    async submit() {
      // const formData = new FormData()
      // formData.append('name', this.name)
      // formData.append('email', this.email)
      // formData.append('password', this.password)
      // axios.post('register', formData, {
      //   headers: { 'Content-Type': 'multipart/form-data' },
      // })
      await this.$axios
        .$post('register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then(() => {
          this.$router.push('/')
        })
    },
  },
}
</script>
