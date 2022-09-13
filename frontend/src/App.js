import './App.css';
import Header from "./components/header/Header";
import {BrowserRouter} from 'react-router-dom';
import Body from "./components/Home";
import MyRoutes from "./routes";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Header/>
        <MyRoutes />
      </BrowserRouter>
    </div>
  );
}

export default App;
