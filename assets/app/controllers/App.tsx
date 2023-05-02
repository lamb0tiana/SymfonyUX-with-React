import React from 'react'
import Login from '../components/login'
import AuthWrapperContextComponent from '../context/authContext'

const App = (props) => {
  return (
    <AuthWrapperContextComponent>
      <Login />
    </AuthWrapperContextComponent>
  )
}

export default App
