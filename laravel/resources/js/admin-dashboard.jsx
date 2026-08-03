import React, {useCallback, useEffect, useMemo, useState} from 'react';
import ReactDOM from 'react-dom';
import {
  Area,
  AreaChart,
  Bar,
  BarChart,
  CartesianGrid,
  Cell,
  Legend,
  Line,
  LineChart,
  ResponsiveContainer,
  Tooltip,
  XAxis,
  YAxis,
} from 'recharts';

const moneyFormatter = new Intl.NumberFormat('es-AR', {
  style: 'currency',
  currency: 'ARS',
  minimumFractionDigits: 2,
});

const numberFormatter = new Intl.NumberFormat('es-AR');
const STATUS_COLORS = ['#002FA7', '#E4002B', '#111827', '#4B5563', '#7C8AA0', '#9CA3AF'];

function formatMoney(value) {
  return moneyFormatter.format(Number(value || 0));
}

function formatNumber(value) {
  return numberFormatter.format(Number(value || 0));
}

function shortLabel(value, max = 22) {
  const text = String(value || 'Sin dato');
  return text.length > max ? `${text.slice(0, max - 1)}...` : text;
}

function statusTone(status) {
  const normalized = String(status || '').toLowerCase();

  if (normalized.includes('facturado')) {
    return 'is-success';
  }

  if (normalized.includes('aprobado')) {
    return 'is-blue';
  }

  if (normalized.includes('cancel')) {
    return 'is-danger';
  }

  if (normalized.includes('pendiente')) {
    return 'is-warning';
  }

  return 'is-neutral';
}

function MetricCard({metric}) {
  return (
    <article className={`rd-metric ${metric.alert ? 'is-alert' : ''}`}>
      <div className="rd-metric__top">
        <span>{metric.label}</span>
        <i className={metric.icon} aria-hidden="true" />
      </div>
      <strong>{metric.money ? formatMoney(metric.value) : formatNumber(metric.value)}</strong>
      <small>{metric.hint}</small>
    </article>
  );
}

function EmptyState({children}) {
  return <div className="rd-empty">{children}</div>;
}

function SalesDashboard({data}) {
  const metrics = data.metrics || {};
  const estados = data.estados || [];
  const recientes = data.ultimos_pedidos || [];
  const clientes = data.clientes_frecuentes || [];
  const tendencia = data.pedidos_por_dia || [];
  const modules = data.modulos || [];

  const metricCards = useMemo(() => ([
    {
      label: 'Pedidos totales',
      value: metrics.pedidos_total,
      hint: 'Historico registrado',
      icon: 'fas fa-clipboard-list',
    },
    {
      label: 'Pendientes',
      value: metrics.pedidos_pendientes,
      hint: 'Esperando revision',
      icon: 'fas fa-clock',
      alert: Number(metrics.pedidos_pendientes || 0) > 0,
    },
    {
      label: 'Aprobados',
      value: metrics.pedidos_aprobados,
      hint: 'Listos para facturar',
      icon: 'fas fa-check-circle',
    },
    {
      label: 'Facturados',
      value: metrics.pedidos_facturados,
      hint: 'Con comprobantes',
      icon: 'fas fa-file-invoice-dollar',
    },
    {
      label: 'Facturacion total',
      value: metrics.facturacion_total,
      hint: 'Historico facturado',
      icon: 'fas fa-dollar-sign',
      money: true,
    },
    {
      label: 'Facturacion mensual',
      value: metrics.facturacion_mensual,
      hint: 'Mes en curso',
      icon: 'fas fa-calendar-alt',
      money: true,
    },
    {
      label: 'Clientes activos',
      value: metrics.clientes_activos,
      hint: 'Zona clientes',
      icon: 'fas fa-users',
    },
    {
      label: 'Productos activos',
      value: metrics.productos_activos,
      hint: 'Catalogo visible',
      icon: 'fas fa-box-open',
    },
    {
      label: 'Stock critico',
      value: metrics.stock_critico,
      hint: 'Presentaciones sin stock',
      icon: 'fas fa-exclamation-triangle',
      alert: Number(metrics.stock_critico || 0) > 0,
    },
  ]), [metrics]);

  const clientChartData = useMemo(() => (
    clientes.map((cliente) => ({
      ...cliente,
      cliente_corto: shortLabel(cliente.cliente, 20),
    }))
  ), [clientes]);

  return (
    <>
      <section className="rd-metrics" aria-label="Indicadores principales">
        {metricCards.map((metric) => (
          <MetricCard key={metric.label} metric={metric} />
        ))}
      </section>

      <section className="rd-grid rd-grid--charts">
        <article className="rd-panel rd-panel--large">
          <div className="rd-panel__head">
            <div>
              <span>Ultimos 14 dias</span>
              <h2>Ingreso de pedidos</h2>
            </div>
          </div>
          <div className="rd-chart">
            {tendencia.length ? (
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={tendencia} margin={{top: 10, right: 12, left: -18, bottom: 0}}>
                  <defs>
                    <linearGradient id="ordersFill" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#002FA7" stopOpacity={0.28} />
                      <stop offset="95%" stopColor="#002FA7" stopOpacity={0.02} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid stroke="#E3E8F2" vertical={false} />
                  <XAxis dataKey="fecha" tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} />
                  <YAxis tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} allowDecimals={false} />
                  <Tooltip
                    cursor={{stroke: '#002FA7', strokeWidth: 1}}
                    formatter={(value) => [formatNumber(value), 'Pedidos']}
                    labelFormatter={(label) => `Fecha ${label}`}
                  />
                  <Area type="monotone" dataKey="pedidos" name="Pedidos" stroke="#002FA7" strokeWidth={3} fill="url(#ordersFill)" activeDot={{r: 5}} />
                </AreaChart>
              </ResponsiveContainer>
            ) : (
              <EmptyState>Sin pedidos recientes para graficar.</EmptyState>
            )}
          </div>
        </article>

        <article className="rd-panel">
          <div className="rd-panel__head">
            <div>
              <span>Ventas por dia</span>
              <h2>Facturacion registrada</h2>
            </div>
          </div>
          <div className="rd-summary-pills" aria-label="Resumen de facturacion">
            <div>
              <span>Total facturado</span>
              <strong>{formatMoney(metrics.facturacion_total)}</strong>
            </div>
            <div>
              <span>Mes actual</span>
              <strong>{formatMoney(metrics.facturacion_mensual)}</strong>
            </div>
          </div>
          <div className="rd-chart">
            {tendencia.length ? (
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={tendencia} margin={{top: 10, right: 12, left: 4, bottom: 0}}>
                  <defs>
                    <linearGradient id="billingFill" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#E4002B" stopOpacity={0.24} />
                      <stop offset="95%" stopColor="#E4002B" stopOpacity={0.02} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid stroke="#E3E8F2" vertical={false} />
                  <XAxis dataKey="fecha" tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} />
                  <YAxis tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} tickFormatter={(value) => `$${formatNumber(value)}`} />
                  <Tooltip formatter={(value) => [formatMoney(value), 'Facturacion']} labelFormatter={(label) => `Fecha ${label}`} />
                  <Area type="monotone" dataKey="facturacion" name="Facturacion" stroke="#E4002B" strokeWidth={3} fill="url(#billingFill)" activeDot={{r: 5}} />
                </AreaChart>
              </ResponsiveContainer>
            ) : (
              <EmptyState>Sin facturacion reciente para graficar.</EmptyState>
            )}
          </div>
        </article>
      </section>

      <section className="rd-grid rd-grid--charts">
        <article className="rd-panel">
          <div className="rd-panel__head">
            <div>
              <span>Distribucion actual</span>
              <h2>Pedidos por estado</h2>
            </div>
          </div>
          <div className="rd-chart rd-chart--bars">
            {estados.length ? (
              <ResponsiveContainer width="100%" height="100%">
                <BarChart layout="vertical" data={estados} margin={{top: 4, right: 20, left: 12, bottom: 4}}>
                  <CartesianGrid stroke="#E3E8F2" horizontal={false} />
                  <XAxis type="number" tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} />
                  <YAxis
                    type="category"
                    dataKey="estado"
                    width={124}
                    tickLine={false}
                    axisLine={false}
                    tick={{fill: '#111827', fontSize: 12}}
                  />
                  <Tooltip formatter={(value) => [formatNumber(value), 'Pedidos']} />
                  <Bar dataKey="total" radius={[0, 4, 4, 0]}>
                    {estados.map((entry, index) => (
                      <Cell key={entry.estado} fill={STATUS_COLORS[index % STATUS_COLORS.length]} />
                    ))}
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            ) : (
              <EmptyState>No hay estados registrados.</EmptyState>
            )}
          </div>
        </article>

        <article className="rd-panel">
          <div className="rd-panel__head">
            <div>
              <span>Clientes recurrentes</span>
              <h2>Mayor actividad</h2>
            </div>
          </div>
          <div className="rd-chart rd-chart--bars">
            {clientChartData.length ? (
              <ResponsiveContainer width="100%" height="100%">
                <BarChart layout="vertical" data={clientChartData} margin={{top: 4, right: 20, left: 12, bottom: 4}}>
                  <CartesianGrid stroke="#E3E8F2" horizontal={false} />
                  <XAxis type="number" tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} />
                  <YAxis type="category" dataKey="cliente_corto" width={142} tickLine={false} axisLine={false} tick={{fill: '#111827', fontSize: 12}} />
                  <Tooltip
                    formatter={(value, name) => [name === 'total' ? formatMoney(value) : formatNumber(value), name === 'total' ? 'Total' : 'Pedidos']}
                    labelFormatter={(label, payload) => (payload && payload[0] && payload[0].payload.cliente) || label}
                  />
                  <Bar dataKey="pedidos" name="Pedidos" fill="#002FA7" radius={[0, 4, 4, 0]} />
                </BarChart>
              </ResponsiveContainer>
            ) : (
              <EmptyState>No hay clientes frecuentes para mostrar.</EmptyState>
            )}
          </div>
        </article>
      </section>

      <section className="rd-grid">
        <article className="rd-panel rd-panel--large">
          <div className="rd-panel__head">
            <div>
              <span>Movimientos recientes</span>
              <h2>Actividad de pedidos</h2>
            </div>
          </div>
          <div className="rd-table">
            <div className="rd-table__head">
              <span>Nro</span>
              <span>Cliente</span>
              <span>Estado</span>
              <span>Total</span>
            </div>
            {recientes.length ? recientes.map((pedido) => (
              <div className="rd-table__row" key={pedido.id}>
                <strong>#{pedido.id}</strong>
                <div>
                  <strong>{pedido.cliente}</strong>
                  <small>{pedido.fecha}</small>
                </div>
                <span className={`rd-status ${statusTone(pedido.estado)}`}>{pedido.estado || 'Sin estado'}</span>
                <strong className="rd-money">{formatMoney(pedido.total)}</strong>
              </div>
            )) : (
              <EmptyState>No hay pedidos para mostrar.</EmptyState>
            )}
          </div>
        </article>

        <article className="rd-panel rd-access-panel">
          <div className="rd-panel__head">
            <div>
              <span>Permisos visibles</span>
              <h2>Modulos habilitados</h2>
            </div>
          </div>
          <div className="rd-module-list">
            {modules.map((module) => (
              <span key={module.key}>
                <i className={module.icon} aria-hidden="true" />
                {module.label}
              </span>
            ))}
          </div>
        </article>
      </section>
    </>
  );
}

function WebTrafficDashboard({data}) {
  const traffic = data.web_traffic || [];
  const hasTraffic = traffic.some((item) => Number(item.visitas || 0) > 0 || Number(item.busquedas || 0) > 0);

  return (
    <section className="rd-panel rd-panel--traffic">
      <div className="rd-panel__head">
        <div>
          <span>Actividad publica</span>
          <h2>Trafico web</h2>
        </div>
      </div>
      <div className="rd-chart rd-chart--traffic">
        <ResponsiveContainer width="100%" height="100%">
          <LineChart data={traffic} margin={{top: 12, right: 24, left: 0, bottom: 8}}>
            <CartesianGrid stroke="#E3E8F2" vertical={false} />
            <XAxis dataKey="fecha" tickLine={false} axisLine={false} tick={{fill: '#667085', fontSize: 12}} />
            <YAxis tickLine={false} axisLine={false} allowDecimals={false} tick={{fill: '#667085', fontSize: 12}} />
            <Tooltip
              formatter={(value, name) => [formatNumber(value), name === 'busquedas' ? 'Busquedas' : 'Visitas']}
              labelFormatter={(label) => `Fecha ${label}`}
            />
            <Legend />
            <Line type="monotone" dataKey="visitas" name="Visitas" stroke="#002FA7" strokeWidth={3} dot={{r: 3}} activeDot={{r: 6}} />
            <Line type="monotone" dataKey="busquedas" name="Busquedas" stroke="#E4002B" strokeWidth={3} dot={{r: 3}} activeDot={{r: 6}} />
          </LineChart>
        </ResponsiveContainer>
        {!hasTraffic && (
          <div className="rd-chart-note">El trafico empieza a registrarse desde esta actualizacion.</div>
        )}
      </div>
    </section>
  );
}

function Dashboard({initialData, endpoint}) {
  const [data, setData] = useState(initialData);
  const [syncState, setSyncState] = useState('Actualizado');
  const [isLoading, setIsLoading] = useState(false);
  const isSalesDashboard = data.dashboard_type === 'sales';

  const refreshDashboard = useCallback(() => {
    if (!endpoint) {
      return;
    }

    setIsLoading(true);
    setSyncState('Sincronizando');

    fetch(endpoint, {
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      cache: 'no-store',
      credentials: 'same-origin',
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        return response.json();
      })
      .then((payload) => {
        setData(payload);
        setSyncState('Actualizado');
      })
      .catch(() => {
        setSyncState('Error de conexion');
      })
      .finally(() => {
        setIsLoading(false);
      });
  }, [endpoint]);

  useEffect(() => {
    const interval = window.setInterval(refreshDashboard, 30000);

    return () => window.clearInterval(interval);
  }, [refreshDashboard]);

  return (
    <div className={`react-admin-dashboard ${isSalesDashboard ? 'is-sales-dashboard' : 'is-web-dashboard'}`}>
      <header className="rd-header">
        <div>
          <span className="rd-eyebrow">{isSalesDashboard ? 'Ventas y pedidos' : 'Trafico web'}</span>
          <h1>{isSalesDashboard ? 'Dashboard comercial' : 'Actividad del sitio web'}</h1>
          <p>
            {isSalesDashboard
              ? 'Metricas de ventas, pedidos, clientes y facturacion para Pablo/Pablito.'
              : 'Vista simplificada con trafico publico del sitio.'}
          </p>
        </div>
        <div className="rd-header__actions">
          <div className="rd-sync">
            <span className={`rd-dot ${syncState === 'Error de conexion' ? 'is-error' : ''}`} />
            <div>
              <small>{syncState}</small>
              <strong>{data.updated_at}</strong>
            </div>
          </div>
          <button className="rd-button" type="button" onClick={refreshDashboard} disabled={isLoading}>
            <i className={`fas fa-sync-alt ${isLoading ? 'fa-spin' : ''}`} aria-hidden="true" />
            <span>Actualizar</span>
          </button>
        </div>
      </header>

      {isSalesDashboard ? (
        <SalesDashboard data={data} />
      ) : (
        <WebTrafficDashboard data={data} />
      )}
    </div>
  );
}

const root = document.getElementById('admin-dashboard-root');

if (root) {
  const initialNode = document.getElementById('admin-dashboard-data');
  const initialData = initialNode ? JSON.parse(initialNode.textContent) : {};

  ReactDOM.render(
    <Dashboard initialData={initialData} endpoint={root.dataset.endpoint} />,
    root
  );
}
