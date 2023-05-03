import React from 'react'
import {
  createBrowserRouter,
  RouterProvider,
  useLoaderData,
} from 'react-router-dom'
import App from './App'
import Login from '../components/login'
import PlayerDataTable from '../components/Players/PlayerDataTable'
const appRouter = createBrowserRouter([
  {
    path: '/',
    element: <App />,
  },
  {
    path: '/dashboard',
    element: <PlayerDataTable />,
  },
])

const AppRouter = () => (
  <RouterProvider router={appRouter} fallbackElement={'loading'} />
)
export default AppRouter
