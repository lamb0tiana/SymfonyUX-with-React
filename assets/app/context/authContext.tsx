import React, { useContext, useReducer } from 'react'
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

  return (
    <AuthContext.Provider value={{ token, dispatch }}>
      {children}
    </AuthContext.Provider>
  )
}

export default AuthWrapperContextComponent

const useAuth = () => useContext<AuthContextInterface>(AuthContext)
export { useAuth }
