import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class VoucherService {
  private apiUrl = 'https://hiring.pruebasgt.com/api/vouchers';

  constructor(private http: HttpClient) {}

  generarComprobante(payload: any) {
    return this.http.post<any>(this.apiUrl, payload);
  }
}
