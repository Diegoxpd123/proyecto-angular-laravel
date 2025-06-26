import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class BranchService {
  private api = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  getAll(): Observable<any[]> {
    const tenantId = localStorage.getItem('tenant_id');
    const headers = new HttpHeaders({
      'X-Tenant-ID': tenantId || ''
    });

    return this.http.get<any[]>(this.api+'/branches', { headers });
  }
}
