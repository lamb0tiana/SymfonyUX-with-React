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
  const login = () => navigate('/login')
  return (
    <>
      <Box sx={{ flexGrow: 1 }}>
        <AppBar position="static">
          <Toolbar>
            <Box
              sx={{
                marginLeft: 'auto',
              }}
            >
              <Button color="inherit">
                {token ? (
                  <Typography onClick={logout} component={'span'}>
                    {' '}
                    <LogoutIcon /> Logout
                  </Typography>
                ) : (
                  <Typography onClick={login}>
                    <LoginOutlined /> LogIn
                  </Typography>
                )}
              </Button>
            </Box>
          </Toolbar>
        </AppBar>
      </Box>
      <Outlet />
    </>
  )
}
export default Layout
