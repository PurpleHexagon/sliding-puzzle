import axios from 'axios'
import { merge } from 'ramda'

// ------------------------------------
// Constants
// ------------------------------------
export const START_PUZZLE = 'START_PUZZLE';
export const MOVE = 'MOVE';

// ------------------------------------
// Actions
// ------------------------------------

/*  This is a thunk, meaning it is a function that immediately
    returns a function for lazy evaluation. It is incredibly useful for
    creating async actions, especially when combined with redux-thunk! */

export const startPuzzle = () => {
  return (dispatch, getState) => {
    return axios.get('http://0.0.0.0:8080/start-puzzle')
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
    return axios.post(
      'http://0.0.0.0:8080/move',
      JSON.stringify({ moveArray }),
      {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
    .then(function (response) {
      return dispatch({
        type    : MOVE,
        payload : response.data
      })
    })
    .catch(function (error) {
      console.log(error)
    })
  }
}

export const actions = {
  moveBlock
}

// ------------------------------------
// Action Handlers
// ------------------------------------
const ACTION_HANDLERS = {
  [START_PUZZLE] : (state, action) => {
    console.log(state)
    return merge(state, action.payload)
  },
  [MOVE] : (state, action) => {
    return merge(state, action.payload)
  }
}

// ------------------------------------
// Reducer
// ------------------------------------
const initialState = { tiles: [] };
export default function puzzleReducer (state = initialState, action) {
  const handler = ACTION_HANDLERS[action.type];

  return handler ? handler(state, action) : state
}
