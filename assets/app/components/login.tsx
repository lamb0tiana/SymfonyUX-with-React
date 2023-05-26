import React, { ChangeEvent, useEffect, useState } from 'react'
import { Button, Grid, Link, TextField, Typography } from '@material-ui/core'
import { validateToken } from '../context/authContext'
import { useNavigate } from 'react-router-dom'
import { doQuery, QueryMethod } from '../utils'
import { useLoginMutation } from '../../queries/graphql'

const Login = () => {
  const [creds, setCredentials] = useState<{ email: string; password: string }>(
    { email: 'demo@dev.mg', password: 'demo' }
  )
  const [errors, setErrors] = useState<string[]>([])
  const [isRegister, setIsRegister] = useState<boolean>(false)
  const navigate = useNavigate()
  const [auth, { data, loading, error }] = useLoginMutation({
    variables: { input: creds },
  })
  const handleSubmit = async (event) => {
    event.preventDefault()
    await auth({
      onCompleted: ({
        _AppAuthentication: {
          appAuthentication: { authPayloads },
        },
      }) => {
        if (authPayloads.__typename === 'Authenticated') {
          validateToken(authPayloads.token) && navigate('/')
        } else {
          errors.push(authPayloads.error)
          setErrors(errors)
        }
      },
    })
  }
  const clearErrors = () => setErrors([])
  const handleFieldChange = (e: ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target
    clearErrors()
    setCredentials({ ...creds, [name]: value })
  }

  useEffect(() => {
    clearErrors()
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
