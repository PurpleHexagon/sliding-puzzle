import { merge } from 'ramda'
import appConfig from './app-config.json'

const defaultConfig = {
  appBaseUrl: 'http://0.0.0.0:8080'
}

export default merge(defaultConfig, appConfig)