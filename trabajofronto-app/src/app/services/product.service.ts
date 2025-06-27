import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProductService {
  private api = 'https://moving-firefly-neatly.ngrok-free.app/api';

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

  getAll(): Observable<any[]> {
    return this.http.get<any[]>(`${this.api}/products`, {
      headers: this.getHeaders()
    });
  }

  getById(id: number): Observable<any> {
    return this.http.get<any>(`${this.api}/products/${id}`, {
      headers: this.getHeaders()
    });
  }

  create(data: any): Observable<any> {
    return this.http.post<any>(`${this.api}/products`, data, {
      headers: this.getHeaders()
    });
  }

  update(id: number, data: any): Observable<any> {
    return this.http.put<any>(`${this.api}/products/${id}`, data, {
      headers: this.getHeaders()
    });
  }

  delete(id: number): Observable<any> {
    return this.http.delete<any>(`${this.api}/products/${id}`, {
      headers: this.getHeaders()
    });
  }
}
