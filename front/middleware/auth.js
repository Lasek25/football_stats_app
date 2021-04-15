export default function ({ $apolloHelpers, redirect }) {
  if (!$apolloHelpers.getToken()) {
    return redirect('/login')
  }
}
