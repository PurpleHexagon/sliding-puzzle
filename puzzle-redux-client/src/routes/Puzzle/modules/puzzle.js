import axios from 'axios'
import { merge } from 'ramda'
import appConfig from '../../../../app-config'

// ------------------------------------
// Constants
// ------------------------------------
export const START_PUZZLE = 'START_PUZZLE'
export const MOVE = 'MOVE'
export const MOVE_COUNT = 'MOVE_COUNT'
export const RESET_MOVE_COUNT = 'RESET_MOVE_COUNT'

// ------------------------------------
// Actions
// ------------------------------------

/*  This is a thunk, meaning it is a function that immediately
    returns a function for lazy evaluation. It is incredibly useful for
    creating async actions, especially when combined with redux-thunk! */

export const resetMoveCount = () => {
  return (dispatch) => {
    return dispatch({
      type    : RESET_MOVE_COUNT,
      payload : {}
    })
  }
}

export const startPuzzle = () => {
  return (dispatch) => {
    return axios.get(`${appConfig.appBaseUrl}/start-puzzle`)
    .then(function (response) {
      return dispatch({
        type    : START_PUZZLE,
        payload : response.data
      })
    })
  }
}

export const moveBlock = (moveArray) => {
  return (dispatch, getState) => {
    const state = getState()
    return axios.post(
      `${appConfig.appBaseUrl}/move`,
      JSON.stringify({ moveArray, token: state.puzzle.token }),
      {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
    .then(function (response) {
      dispatch({
        type    : MOVE,
        payload : response.data
      })

      dispatch({
        type    : MOVE_COUNT,
        payload: {}
      })
    })
    .catch(function (error) {
      console.log(error)
    })
  }
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [START_PUZZLE] : (state, action) => {
    return merge(state, action.payload)
  },
  [MOVE] : (state, action) => {
    return merge(state, action.payload)
  },
  [MOVE_COUNT] : (state, action) => {
    return merge(state, { moveCount: state.moveCount + 1 })
  },
  [RESET_MOVE_COUNT] : (state) => {
    return merge(state, { moveCount: 0 })
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = { tiles: [], moveCount: 0, isSolved: false }
export default function puzzleReducer (state = initialState, action = { type: null }) {
  const handler = ACTION_HANDLERS[action.type]

  return handler ? handler(state, action) : state
}
