import { useNavigate } from 'react-router-dom'
import { AppBar, Box, Button, Toolbar, Typography } from '@mui/material'
import LogoutIcon from '@mui/icons-material/Logout'
import React from 'react'
import { useAuth } from '../context/authContext'

const Layout = ({ children }) => {
  const navigate = useNavigate()
  const { dispatch } = useAuth()
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
              <LogoutIcon onClick={logout} />
            </Button>
          </Toolbar>
        </AppBar>
      </Box>

      {children}
    </>
  )
}
export default Layout
