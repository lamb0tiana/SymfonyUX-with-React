import axios from 'axios'
enum QueryMethod {
  POST = 'post',
  GET = 'get',
}
const doQuery = async (
  route: string,
  method = QueryMethod.GET,
  payloads: unknown = null
): Promise<{ data: any; status: number }> => {
  const { data, status } = await axios[method](route, payloads).catch((e) => {
    return e.response
  })

  return { data, status }
}

const getRefreshedToken = async () => {
  const route = `${process.env.API_URL}/me`
  const { data, status } = await doQuery(route)
  if (status === 200) {
    return data
  }
  return { token: null }
}

const getRandomInt = (min, max) => {
  min = Math.ceil(min)
  max = Math.floor(max)
  return Math.floor(Math.random() * (max - min) + min)
}
export { doQuery, QueryMethod, getRandomInt, getRefreshedToken }
