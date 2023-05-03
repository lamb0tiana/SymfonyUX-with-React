import React, { useEffect } from 'react'
import Login from '../components/login'
import { useNavigate } from 'react-router-dom'

import AuthWrapperContextComponent, {
  useAuth,
  validateToken,
} from '../context/authContext'
import PlayerDataTable from '../components/Teams/TeamDataTable'

const App = (props) => {
  const { dispatch } = useAuth()
  const navigate = useNavigate()
  const token = localStorage.getItem('app_token')
  const isValidToken = validateToken(token)
  if (isValidToken) {
    dispatch({ token })
  }
  useEffect(() => {
    if (isValidToken) navigate('/dashboard')
  }, [])

  return (
    <AuthWrapperContextComponent>
      <Login />
    </AuthWrapperContextComponent>
  )
}

export default App
