import React from 'react'
import {
  createBrowserRouter,
  RouterProvider,
  useLoaderData,
} from 'react-router-dom'
import App from './App'
import Login from '../components/login'
import TeamList from '../components/Teams/List'
const appRouter = createBrowserRouter([
  {
    path: '/',
    element: <App />,
  },
  {
    path: '/dashboard',
    element: <TeamList />,
  },
])

const AppRouter = () => (
  <RouterProvider router={appRouter} fallbackElement={'loading'} />
)
export default AppRouter
