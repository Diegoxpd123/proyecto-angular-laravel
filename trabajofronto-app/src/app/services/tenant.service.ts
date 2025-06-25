// tenant.service.ts
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Tenant } from '../models/tenant.model';

@Injectable({ providedIn: 'root' })
export class TenantService {
  private selectedTenantId: string | null = null;
  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient
  ) { }

  setTenant(tenantId: string): void {
    this.selectedTenantId = tenantId;
    localStorage.setItem('tenant_id', tenantId);
  }

  getTenant(): string | null {
    return this.selectedTenantId || localStorage.getItem('tenant_id');
  }

  getTenants(): Observable<Tenant[]> {
    return this.http.get<Tenant[]>(`${this.API_URL}/tenants`);
  }

  createTenat(data: Tenant): Observable<Tenant> {
    return this.http.post<Tenant>(`${this.API_URL}/tenants`, data);
  }


}
