// public/firebase-messaging-sw.js

importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

// Your Firebase config (same as in your HTML)
const firebaseConfig = {
    apiKey: "AIzaSyAQSazOkww2T7W1DzH6Mg8J-v-ksWW4Se0",
    authDomain: "caartl.firebaseapp.com",
    projectId: "caartl",
    storageBucket: "caartl.firebasestorage.app",
    messagingSenderId: "919267898292",
    appId: "1:919267898292:web:ea7aae0efa897e1cfd78e1",
    measurementId: "G-RNVXK253ZN"
};

firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging
const messaging = firebase.messaging();
