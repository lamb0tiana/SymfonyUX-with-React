import { useNavigate, Outlet } from 'react-router-dom'
import { AppBar, Box, Button, Toolbar, Typography } from '@mui/material'
import LogoutIcon from '@mui/icons-material/Logout'
import React from 'react'
import { useAuth } from '../context/authContext'
import { LoginOutlined } from '@mui/icons-material'

const Layout = () => {
  const navigate = useNavigate()
  const { dispatch, token, payloads } = useAuth()
  const logout = () => {
    localStorage.removeItem('app_token')
    dispatch({ token: null })
    navigate('/')
  }
  return (
    <>
      <Box sx={{ flexGrow: 1 }}>
        <AppBar position="static">
          <Toolbar>
            <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
              Team
            </Typography>
            <Button color="inherit">
              {token ? <LogoutIcon onClick={logout} /> : <LoginOutlined />}
            </Button>
          </Toolbar>
        </AppBar>
      </Box>
      <Outlet />
    </>
  )
}
export default Layout
