import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

export interface LoginResponse {
  access_token: string;
  token_type: string;
  expires_in: number;
  user: any; // Puedes crear una interfaz más específica si deseas
  tenant_id: number;
}

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private api = `http://localhost:8000/api`;

  constructor(private http: HttpClient) { }

  login(credentials: { email: string; password: string }) {
    return this.http.post<LoginResponse>(this.api + '/auth/login', credentials);
  }

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('tenant_id');
  }

  isAuthenticated(): boolean {
    return !!localStorage.getItem('token');
  }
}
