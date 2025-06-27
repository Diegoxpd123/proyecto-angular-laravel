import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class GenreService {

  private api = 'http://localhost:8000/api';

  constructor(private http: HttpClient) { }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    const tenantId = localStorage.getItem('tenant_id');

    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'X-Tenant-ID': tenantId || '',
      'Content-Type': 'application/json'
    });
  }
  getAll() {
    return this.http.get<any[]>(this.api+'/genres', {
      headers: this.getHeaders()
    });
  }

  getById(id: number) {
    return this.http.get<any>(`${this.api}/genres/${id}`, {
      headers: this.getHeaders()
    });
  }

  create(data: any) {
    return this.http.post<any>(this.api+'/genres', data, {
      headers: this.getHeaders()
    });
  }

  update(id: number, data: any) {
    return this.http.put<any>(`${this.api}/genres/${id}`, data, {
      headers: this.getHeaders()
    });
  }

  delete(id: number) {
    return this.http.delete<any>(`${this.api}/genres/${id}`, {
      headers: this.getHeaders()
    });
  }
}
