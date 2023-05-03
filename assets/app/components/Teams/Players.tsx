import { Routes, Route, useParams } from 'react-router-dom'
import React from 'react'
const Players = () => {
  const a = useParams()

  return <>{JSON.stringify(a)}</>
}

export default Players
