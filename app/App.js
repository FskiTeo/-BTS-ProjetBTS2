import { NavigationContainer } from '@react-navigation/native';
import { StatusBar } from 'expo-status-bar';
import { StyleSheet, Text, View, Image } from 'react-native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import Login from './screens/Login';
import Home from './screens/Home';
import AddItem from './screens/AddItem';
import Stock from './screens/Stock';  
import EditItem from './screens/EditItem';

const Logo = './assets/SneakHubLogo.PNG';

const Stack = createNativeStackNavigator();

export default function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Login" screenOptions={{headerTitleAlign: 'center',}}>
        <Stack.Screen 
          name="Login" 
          component={Login} 
          options={{headerTitle: props => <Image source={Logo} style={{ width: 100, height: 55}} />}} 
        />
        <Stack.Screen 
          name="Home" 
          component={Home} 
          options={{headerTitle: props => <Image source={Logo} style={{ width: 100, height: 55}} />}} 
        />
        <Stack.Screen 
          name="AddItem" 
          component={AddItem} 
          options={{headerTitle: props => <Image source={Logo} style={{ width: 100, height: 55}} />}} 
        />
        <Stack.Screen 
          name="Stock" 
          component={Stock} 
          options={{headerTitle: props => <Image source={Logo} style={{ width: 100, height: 55}} />}} 
        />
        <Stack.Screen 
          name="EditItem" 
          component={EditItem} 
          options={{headerTitle: props => <Image source={Logo} style={{ width: 100, height: 55}} />}} 
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    alignItems: 'center',
    justifyContent: 'center',
  },
});