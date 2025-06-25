import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { UsuarioTenant } from '../models/usuariotenant.model';

@Injectable({
  providedIn: 'root'
})
export class UsuariotenantService {
  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient
  ) { }

  getUsuariosTenants(): Observable<UsuarioTenant[]> {
    return this.http.get<UsuarioTenant[]>(`${this.API_URL}/usuariostenants`);
  }

  getUsuarioTenant(id: number): Observable<UsuarioTenant> {
    return this.http.get<UsuarioTenant>(`${this.API_URL}/usuariostenants/${id}`);
  }

  createUsuarioTenant(data: UsuarioTenant): Observable<UsuarioTenant> {
    return this.http.post<UsuarioTenant>(`${this.API_URL}/usuariostenants`, data);
  }

  updateUsuarioTenant(id: number, data: UsuarioTenant): Observable<UsuarioTenant> {
    return this.http.put<UsuarioTenant>(`${this.API_URL}/usuariostenants/${id}`, data);
  }

  deleteUsuarioTenant(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/usuariostenants/${id}`);
  }
}
