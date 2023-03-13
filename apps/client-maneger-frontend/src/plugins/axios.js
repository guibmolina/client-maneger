// axios
import axios from 'axios';

const baseURL = 'http://localhost:8000/api/v1/';

const axiosIns = axios.create({
  baseURL,
});



export default axiosIns;
