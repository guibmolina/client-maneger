// axios
import axios from 'axios';

const baseURL = 'http://localhost/api/v1/';

const axiosIns = axios.create({
  baseURL,
});



export default axiosIns;
