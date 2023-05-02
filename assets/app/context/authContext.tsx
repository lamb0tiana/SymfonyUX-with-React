import React, { useContext, useReducer } from 'react'
import jwt_decode from 'jwt-decode'

import axios from 'axios'
interface AuthContextInterface {
  token: string | null
  dispatch: Function
}

const defaultValue = {
  token: null,
  dispatch: (token: string) => token,
}

const AuthContext: React.Context<AuthContextInterface> =
  React.createContext<AuthContextInterface>(defaultValue)

const AuthWrapperContextComponent = ({ children }) => {
  const [{ token }, dispatch] = useReducer(
    (stateA, statB) => ({ ...stateA, ...statB }),
    defaultValue
  )
  if (token) {
    const decoded: { exp: number } = jwt_decode(token)

    if (decoded.exp > new Date().getTime() / 1000) {
      localStorage.setItem('app_token', token)
      axios.defaults.headers['Authorization'] = `Bearer ${token}`
    } else {
      delete axios.defaults.headers['Authorization']
      localStorage.removeItem('app_token')
    }
  }
  return (
    <AuthContext.Provider value={{ token, dispatch }}>
      {children}
    </AuthContext.Provider>
  )
}

export default AuthWrapperContextComponent

const useAuth = () => useContext<AuthContextInterface>(AuthContext)
export { useAuth }
