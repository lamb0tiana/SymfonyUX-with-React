import { useNavigate, Outlet } from 'react-router-dom'
import { AppBar, Box, Button, Link, Toolbar, Typography } from '@mui/material'
import LogoutIcon from '@mui/icons-material/Logout'
import React from 'react'
import {
  AuthContextInterface,
  PayloadType,
  useAuth,
  validateToken,
} from '../context/authContext'
import { LoginOutlined } from '@mui/icons-material'
import jwt_decode from 'jwt-decode'
import './layout.scss'
const Layout = () => {
  const navigate = useNavigate()
  const { dispatch, token, payloads } = useAuth()
  const storeToken = localStorage.getItem('app_token')
  const hasValidToken = validateToken(storeToken)
  const paylods: PayloadType = hasValidToken ? jwt_decode(storeToken) : null

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
            {hasValidToken ? (
              <Box>
                <img
                  loading="lazy"
                  width="20"
                  src={`https://flagcdn.com/w20/${paylods.team.countryCode.toLowerCase()}.png`}
                  srcSet={`https://flagcdn.com/w40/${paylods.team.countryCode.toLowerCase()}.png 2x`}
                  alt=""
                />
                <Typography component={'span'} ml={'10px'}>
                  {paylods.team.name} | $
                  {paylods.team.moneyBalance.toLocaleString('en-US')}
                </Typography>
              </Box>
            ) : (
              ''
            )}
            <Box
              sx={{
                marginLeft: 'auto',
              }}
            >
              {hasValidToken ? (
                <>
                  <Button color={'inherit'} onClick={() => navigate('/')}>
                    <Typography component={'span'}>Teams</Typography>
                  </Button>
                  |
                </>
              ) : (
                ''
              )}
              <Button color="inherit">
                {token ? (
                  <Typography onClick={logout} component={'span'}>
                    {' '}
                    <LogoutIcon className={'userActionMenu'} /> Logout
                  </Typography>
                ) : (
                  <Typography onClick={login} component={'span'}>
                    <LoginOutlined className={'userActionMenu'} /> LogIn
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
