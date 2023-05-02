import axios from 'axios'

const setAppToekn = (token) => {
  localStorage.setItem('app_token', token)
  axios.defaults.headers['Authorization'] = `Bearer ${token}`
}
export { setAppToekn }
