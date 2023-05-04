import axios from 'axios'
enum QueryMethode {
  POST = 'post',
  GET = 'get',
}
const doQuery = async (
  route: string,
  method = QueryMethode.GET,
  payloads: unknown = null
): Promise<{ data: any; status: number }> => {
  const { data, status } = await axios[method](route, payloads).catch(
    (e) => e.response
  )

  return { data, status }
}

const getRandomInt = (min, max) => {
  min = Math.ceil(min)
  max = Math.floor(max)
  return Math.floor(Math.random() * (max - min) + min)
}
export { doQuery, QueryMethode, getRandomInt }
