import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Usuario } from '../models/usuario.model';

@Injectable({
  providedIn: 'root'
})
export class UsuarioService {


  private selectedUsuarioId: number | null = null;
  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient
  ) { }

  setUsuario(userid: number): void {
    this.selectedUsuarioId = userid;
    localStorage.setItem('id', userid.toString());
  }

  getUsuarioid(): number | null {
    const localId = localStorage.getItem('id');
    return this.selectedUsuarioId || (localId ? parseInt(localId, 10) : null);
  }

  getUsuarios(): Observable<Usuario[]> {
    return this.http.get<Usuario[]>(`${this.API_URL}/usuarios`);
  }

  getUsuario(id: number): Observable<Usuario> {
    return this.http.get<Usuario>(`${this.API_URL}/usuarios/${id}`);
  }

  createUsuario(data: Usuario): Observable<Usuario> {
    return this.http.post<Usuario>(`${this.API_URL}/usuarios`, data);
  }

  updateUsuario(id: number, data: Usuario): Observable<Usuario> {
    return this.http.put<Usuario>(`${this.API_URL}/usuarios/${id}`, data);
  }

  deleteUsuario(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/usuarios/${id}`);
  }
}
