import React, { ChangeEvent, useEffect, useState } from 'react'
import { Button, Grid, Link, TextField, Typography } from '@material-ui/core'
import { validateToken } from '../context/authContext'
import { useNavigate } from 'react-router-dom'
import { doQuery, QueryMethode } from '../utils'

const Login = () => {
  const [creds, setCredentials] = useState<{ email: string; password: string }>(
    { email: 'demo@dev.mg', password: 'demo' }
  )
  const [errors, setErrors] = useState<string[]>([])
  const [isRegister, setIsRegister] = useState<boolean>(false)
  const navigate = useNavigate()

  const handleSubmit = async (event) => {
    event.preventDefault()
    const credentialRoute = `${process.env.API_URL}/${
      isRegister ? 'manager/create' : 'authentication'
    }`
    const { data, status } = await doQuery(
      credentialRoute,
      QueryMethode.POST,
      creds
    )
    let response = []
    switch (status) {
      case 400:
        response.push(data.errors)
        break
      case 401:
        response.push(data.message)
        break
      default:
        const { token } = data
        validateToken(token) && navigate('/')
    }

    response.length > 0 && setErrors(response)
  }

  const handleFieldChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name } = e.target
    setCredentials({ ...creds, [name]: e.target.value })
  }

  useEffect(() => {
    setErrors([])
  }, [isRegister])

  useEffect(() => {
    const token = localStorage.getItem('app_token')
    const isValidToken = validateToken(token)
    if (isValidToken) navigate('/')
  }, [])

  return (
    <div
      style={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
      }}
    >
      <form onSubmit={handleSubmit} style={{ width: '100%', maxWidth: 400 }}>
        {errors.map((error, index) => (
          <Typography
            key={index}
            variant="body1"
            color="error"
            align={'center'}
          >
            {error}
          </Typography>
        ))}
        <Typography variant="h4" align="center" gutterBottom>
          {isRegister ? 'New account manager' : 'Manager login'}
        </Typography>
        <TextField
          value={creds.email}
          onChange={handleFieldChange}
          variant="outlined"
          margin="normal"
          fullWidth
          id="email"
          label="Email address"
          name="email"
          autoComplete="off"
          autoFocus
        />
        <TextField
          variant="outlined"
          margin="normal"
          fullWidth
          value={creds.password}
          onChange={handleFieldChange}
          name="password"
          label="Password"
          type="password"
          id="password"
          autoComplete="current-password"
        />

        <Button
          type="submit"
          fullWidth
          variant="contained"
          color="primary"
          style={{ marginTop: '1rem' }}
        >
          {isRegister ? 'Register' : 'Login'}
        </Button>
        <Grid container style={{ marginTop: '1rem' }} justifyContent={'center'}>
          <Grid item>
            <Link
              href="#"
              variant="body2"
              onClick={() => setIsRegister(!isRegister)}
            >
              {isRegister ? 'Connexion' : 'New account ?'}
            </Link>
          </Grid>
        </Grid>
      </form>
    </div>
  )
}

export default Login
