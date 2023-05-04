import Layout from './Layout'
import AppRouter from './AppRouter'
import AuthWrapperContextComponent from '../context/authContext'

const App = () => (
  <AuthWrapperContextComponent>
    <AppRouter />
  </AuthWrapperContextComponent>
)
export default App
