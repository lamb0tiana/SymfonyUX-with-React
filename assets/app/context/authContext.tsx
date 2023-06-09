import React, { useContext, useReducer } from 'react'
import jwt_decode from 'jwt-decode'

import axios from 'axios'
type TeamType = {
  id: number
  name: string
  countryCode: string
  moneyBalance: number
  slug: string
}
type PayloadType = {
  exp: number
  roles: Array<string>
  id: number
  email: string
  team: TeamType
}
interface AuthContextInterface {
  token: string | null
  payloads: PayloadType | null
  dispatch: Function
}

const defaultValue = {
  token: null,
  dispatch: (token: string) => token,
  payloads: null,
}

const AuthContext: React.Context<AuthContextInterface> =
  React.createContext<AuthContextInterface>(defaultValue)

const AuthWrapperContextComponent = ({ children }) => {
  const [{ token, payloads }, dispatch] = useReducer((stateA, statB) => {
    const state: AuthContextInterface = { ...stateA, ...statB }
    return {
      ...state,
      payloads: state.token
        ? jwt_decode<PayloadType>(state.token)
        : defaultValue.payloads,
    }
  }, defaultValue)

  return (
    <AuthContext.Provider value={{ token, payloads, dispatch }}>
      {children}
    </AuthContext.Provider>
  )
}

export default AuthWrapperContextComponent

const useAuth = () => useContext<AuthContextInterface>(AuthContext)

const validateToken = (token: string | null): boolean => {
  if (!token) return false
  const decoded: { exp: number } = jwt_decode(token)

  if (decoded.exp > new Date().getTime() / 1000) {
    localStorage.setItem('app_token', token)
    axios.defaults.headers['Authorization'] = `Bearer ${token}`
    return true
  } else {
    delete axios.defaults.headers['Authorization']
    localStorage.removeItem('app_token')
  }
  return false
}
export { useAuth, validateToken, AuthContextInterface, PayloadType }
