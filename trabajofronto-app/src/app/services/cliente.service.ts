import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Cliente } from '../models/cliente.model';
import { TenantService } from './tenant.service';

@Injectable({
  providedIn: 'root'
})
export class ClienteService {

  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient, private tenantService: TenantService
  ) { }


  getClientess(tenant: string): Observable<Cliente[]> {
    return this.http.get<Cliente[]>(`${this.API_URL}/${tenant}/clientes`);
  }

  getClientes(): Observable<Cliente[]> {
    const tenant = this.tenantService.getTenant();
    const headers = new HttpHeaders().set('tenant-id', tenant || '');

    return this.http.get<Cliente[]>(`${this.API_URL}/clientes`, { headers });
  }

  getCliente(id: number): Observable<Cliente> {
    const tenant = this.tenantService.getTenant();
    const headers = new HttpHeaders().set('tenant-id', tenant || '');
    return this.http.get<Cliente>(`${this.API_URL}/clientes/${id}`, { headers });
  }

  createCliente(data: Cliente): Observable<Cliente> {
    const tenant = this.tenantService.getTenant();
    const headers = new HttpHeaders().set('tenant-id', tenant || '');
    return this.http.post<Cliente>(`${this.API_URL}/clientes`, data, { headers });
  }

  updateCliente(id: number, data: Cliente): Observable<Cliente> {

    const tenant = this.tenantService.getTenant();
    const headers = new HttpHeaders().set('tenant-id', tenant || '');
    return this.http.put<Cliente>(`${this.API_URL}/clientes/${id}`, data, { headers });
  }

  deleteCliente(id: number): Observable<any> {
    const tenant = this.tenantService.getTenant();
    console.log('Tenant enviado en la cabecera:', tenant);
    const headers = new HttpHeaders().set('tenant-id', tenant || '');
    return this.http.delete<any>(`${this.API_URL}/clientes/${id}`, { headers });
  }


}
