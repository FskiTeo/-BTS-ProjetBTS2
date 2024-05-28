// Import the functions you need from the SDKs you need

import firebase from 'firebase/compat/app';
import 'firebase/compat/auth';
import 'firebase/compat/firestore';

// TODO: Add SDKs for Firebase products that you want to use

// https://firebase.google.com/docs/web/setup#available-libraries


// Your web app's Firebase configuration

const firebaseConfig = {

  apiKey: "AIzaSyDzoHMlnM47Q9yqkTFN1-UUmAL92NQnj00",

  authDomain: "stockapp-583e4.firebaseapp.com",

  projectId: "stockapp-583e4",

  storageBucket: "stockapp-583e4.appspot.com",

  messagingSenderId: "861751968798",

  appId: "1:861751968798:web:add5596f745f7983f1da0f"

};

// Initialize Firebase
let app;
if (firebase.apps.length === 0) {
  app = firebase.initializeApp(firebaseConfig);
} else {
  app = firebase.app()
}
 
const auth = firebase.auth()
 
export { auth };