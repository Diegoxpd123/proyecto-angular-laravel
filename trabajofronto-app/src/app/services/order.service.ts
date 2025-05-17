import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Order } from '../models/order.model';

@Injectable({
  providedIn: 'root'
})
export class OrderService {

  private readonly API_URL = 'http://127.0.0.1:8000/api';

  constructor(
    private http: HttpClient
  ) { }

  getOrders(): Observable<Order[]> {
    return this.http.get<Order[]>(`${this.API_URL}/orders`);
  }

  getOrder(id: number): Observable<Order> {
    return this.http.get<Order>(`${this.API_URL}/orders/${id}`);
  }

  createOrder(data: Order): Observable<Order> {
    return this.http.post<Order>(`${this.API_URL}/orders`, data);
  }

  updateOrder(id: number, data: Order): Observable<Order> {
    return this.http.put<Order>(`${this.API_URL}/orders/${id}`, data);
  }

  deleteOrder(id: number): Observable<any> {
    return this.http.delete<any>(`${this.API_URL}/orders/${id}`);
  }
}
