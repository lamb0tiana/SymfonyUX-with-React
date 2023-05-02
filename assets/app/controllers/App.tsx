import React, { useEffect } from 'react'
import Login from '../components/login'
import AuthWrapperContextComponent, {
  useAuth,
  validateToken,
} from '../context/authContext'
import TeamList from '../components/Teams/List'

const App = (props) => {
  const { dispatch } = useAuth()
  const token = localStorage.getItem('app_token')
  const isValidToken = validateToken(token)
  if (isValidToken) {
    dispatch({ token })
  }
  return (
    <AuthWrapperContextComponent>
      {isValidToken ? <TeamList /> : <Login />}
    </AuthWrapperContextComponent>
  )
}

export default App
