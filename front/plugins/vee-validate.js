import { extend } from 'vee-validate'
import { required, email, min, confirmed } from 'vee-validate/dist/rules'

extend('required', {
  ...required,
  message: 'Pole "{_field_}" jest wymagane',
})
extend('min', {
  ...min,
  message: 'Pole "{_field_}" musi mieć długość co najmniej {length} znaków',
})
extend('email', {
  ...email,
  message: 'E-mail musi być prawidłowy',
})
extend('confirmed', {
  ...confirmed,
  message: 'Hasła muszą być takie same',
})
extend('upperCase', {
  validate: (value) => value.match(/[A-ZŻŹĆŃÓŁĘĄŚ]/g) !== null,
  message: 'Hasło musi zawierać min. 1 dużą literę',
})
extend('lowerCase', {
  validate: (value) => value.match(/[a-zżźćńółęąś]/g) !== null,
  message: 'Hasło musi zawierać min. 1 małą literę',
})
extend('num', {
  validate: (value) => value.match(/[0-9]/g) !== null,
  message: 'Hasło musi zawierać min. 1 cyfrę',
})
