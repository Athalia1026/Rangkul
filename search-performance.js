import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  vus: 5,
  duration: '30s',
};

export default function () {
  const baseUrl = __ENV.BASE_URL || 'http://127.0.0.1:8000';
  const response = http.get(
    `${baseUrl}/donatur/hasil-pencarian?q=anak&tab=organisasi`
  );

  check(response, {
    'halaman hasil pencarian berhasil dimuat': result => result.status === 200,
  });
  sleep(5);
}
