import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Detail } from '../models/detail.model';

@Injectable({
  providedIn: 'root'
})
export class DetailService {
  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient
  ) { }

  getDetails(): Observable<Detail[]> {
    return this.http.get<Detail[]>(`${this.API_URL}/details`);
  }

  getDetail(id: number): Observable<Detail> {
    return this.http.get<Detail>(`${this.API_URL}/details/${id}`);
  }

  createDetail(data: Detail): Observable<Detail> {
    return this.http.post<Detail>(`${this.API_URL}/details`, data);
  }

  updateDetail(id: number, data: Detail): Observable<Detail> {
    return this.http.put<Detail>(`${this.API_URL}/details/${id}`, data);
  }

  deleteDetail(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/details/${id}`);
  }
}
